<?php

namespace App\Repository;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function create(string $name, string $email, string $password): User;

    /**
     * @param string $name
     * @return User|null
     */
    public function findByEmail(string $name): ?User;
}
