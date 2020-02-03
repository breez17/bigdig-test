<?php

namespace App\Repository;

use App\Models\Entity;
use App\Models\EntityFile;

interface EntityFileRepositoryInterface
{
    /**
     * @param Entity $entity
     * @return int
     */
    public function countByEntity(Entity $entity): int;

    /**
     * @param Entity $entity
     * @param string $filename
     * @return EntityFile
     */
    public function create(Entity $entity, string $filename): EntityFile;
}
