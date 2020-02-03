<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class UploadChunksController
{
    /**
     * @param FileReceiver $receiver
     * @return JsonResponse
     * @throws UploadMissingFileException
     */
    public function uploadFile(FileReceiver $receiver): JsonResponse
    {
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();


        if ($save->isFinished()) {
            return $this->saveFile($save->getFile());
        }

        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return new JsonResponse([
            'done' => $handler->getPercentageDone()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws UploadMissingFileException
     * @throws \Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException
     */
    public function upload(Request $request): JsonResponse
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return $this->saveFile($save->getFile());
        }

        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return new JsonResponse([
            'done' => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    /**
     * @param UploadedFile $file
     * @return JsonResponse
     */
    protected function saveFile(UploadedFile $file): JsonResponse
    {
        $fileName = $this->createFilename($file);
        $mime = str_replace('/', '-', $file->getMimeType());
        $dateFolder = date('Y-m-W');

        $filePath = "upload/{$mime}/{$dateFolder}/";
        $finalPath = storage_path('app/public/' . $filePath);

        $file->move($finalPath, $fileName);

        return new JsonResponse([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.' . $extension, '', $file->getClientOriginalName());

        $filename .= '' . md5(time()) . '.' . $extension;

        return $filename;
    }
}
