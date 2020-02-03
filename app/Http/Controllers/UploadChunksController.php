<?php

namespace App\Http\Controllers;

use App\Components\UploadChunksComponent;
use App\Models\Entity;
use App\Repository\EntityFileRepositoryInterface;
use App\Repository\EntityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadChunksController
{
    public const MAX_FILE_SIZE = 3 * 1024 * 1024; // 3 GD

    /**
     * @var EntityFileRepositoryInterface
     */
    private $entityFileRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * @var UploadChunksComponent
     */
    private $uploadChunksComponent;

    /**
     * UploadChunksController constructor.
     * @param EntityFileRepositoryInterface $entityFileRepository
     * @param EntityRepositoryInterface $entityRepository
     * @param UploadChunksComponent $uploadChunksComponent
     */
    public function __construct(
        EntityFileRepositoryInterface $entityFileRepository,
        EntityRepositoryInterface $entityRepository,
        UploadChunksComponent $uploadChunksComponent
    )
    {
        $this->entityFileRepository = $entityFileRepository;
        $this->entityRepository = $entityRepository;
        $this->uploadChunksComponent = $uploadChunksComponent;
    }

    /**
     * @param FileReceiver $receiver
     * @param Request $request
     * @return JsonResponse
     * @throws UploadMissingFileException
     */
    public function uploadFile(FileReceiver $receiver, Request $request): JsonResponse
    {
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $resumableTotalSize = $request->query->getInt('resumableTotalSize');

        if (static::MAX_FILE_SIZE < $resumableTotalSize) {
            return new JsonResponse([
                'error' => 'Payload Too Large',
            ], Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $entityId = $request->request->getInt('entity_id');

            $entity = $this->entityRepository->find($entityId);

            if ($entity === null) {
                return new JsonResponse([
                    'error' => 'Entity not found',
                ], Response::HTTP_BAD_REQUEST);
            }

            $countFiles = $this->entityFileRepository->countByEntity($entity);

            if ($countFiles > Entity::MAX_FILES) {
                return new JsonResponse([
                    'error' => 'Max files ' . Entity::MAX_FILES,
                ], Response::HTTP_BAD_REQUEST);
            }

            $entityFile = $this->uploadChunksComponent->saveFile($save->getFile(), $entity);

            return new JsonResponse([
                'entityFile' => $entityFile,
            ]);
        }

        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return new JsonResponse([
            'done' => $handler->getPercentageDone()
        ]);
    }

    /**
     * @return View
     */
    public function form(): View
    {
        return \view('upload.chunks', [
            'entities' => $this->entityRepository->findAll(),
        ]);
    }
}
