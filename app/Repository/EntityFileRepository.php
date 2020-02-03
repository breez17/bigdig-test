<?php

namespace App\Repository;

use App\Models\Entity;
use App\Models\EntityFile;

class EntityFileRepository implements EntityFileRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function countByEntity(Entity $entity): int
    {
        return EntityFile::query()
            ->where('entity_id','=', $entity->id)
            ->count()
        ;
    }

    /**
     * @inheritdoc
     */
    public function create(Entity $entity, string $filename): EntityFile
    {
        $entityFile = new EntityFile();

        $entityFile->entity_id = $entity->id;
        $entityFile->name = $filename;

        $entityFile->save();

        return $entityFile;
    }
}
