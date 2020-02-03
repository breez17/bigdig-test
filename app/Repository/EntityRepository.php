<?php

namespace App\Repository;

use App\Models\Entity;
use Illuminate\Support\Collection;

class EntityRepository implements EntityRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(string $name, \DateTime $date, string $image = null): Entity
    {
        $entity = new Entity();

        $entity->name = $name;
        $entity->date = $date;
        $entity->image = $image;

        $entity->save();

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function findAll(): Collection
    {
        return Entity::query()->with(['files'])->get();
    }

    /**
     * @inheritdoc
     * @return Entity|null|object
     */
    public function find(int $id): ?Entity
    {
        return Entity::query()->find($id);
    }
}
