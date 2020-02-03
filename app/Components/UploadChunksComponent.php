<?php

namespace App\Components;

use App\Models\Entity;
use App\Models\EntityFile;
use App\Repository\EntityFileRepositoryInterface;
use Illuminate\Http\UploadedFile;

class UploadChunksComponent
{
    /**
     * @var EntityFileRepositoryInterface
     */
    private $entityFileRepository;

    /**
     * UploadChunksComponent constructor.
     * @param EntityFileRepositoryInterface $entityFileRepository
     */
    public function __construct(EntityFileRepositoryInterface $entityFileRepository)
    {
        $this->entityFileRepository = $entityFileRepository;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function createFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.' . $extension, '', $file->getClientOriginalName());

        $filename .= '' . md5(time()) . '.' . $extension;

        return $filename;
    }

    /**
     * @param UploadedFile $file
     * @param Entity $entity
     * @return EntityFile
     */
    public function saveFile(UploadedFile $file, Entity $entity): EntityFile
    {
        $fileName = $this->createFilename($file);
        $dateFolder = date('Y-m-W');

        $finalPath = storage_path('app/public/upload/' . $dateFolder . '/');

        $file->move($finalPath, $fileName);

        return $this->entityFileRepository->create($entity, $fileName);
    }
}
