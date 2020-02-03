<?php

namespace App\Repository;

use App\Models\Entity;
use Illuminate\Support\Collection;

interface EntityRepositoryInterface
{
    /**
     * @param string $name
     * @param \DateTime $date
     * @param string|null $image
     * @return Entity
     */
    public function create(string $name, \DateTime $date, string $image = null): Entity;

    /**
     * @return Collection
     */
    public function findAll(): Collection;

    /**
     * @param int $id
     * @return Entity|null
     */
    public function find(int $id): ?Entity;
}
