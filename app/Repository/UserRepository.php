<?php

namespace App\Repository;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(string $name, string $email, string $password): User
    {
        $user = new User();

        $user->name = $name;
        $user->email = $email;
        $user->password = $password;

        $user->save();

        return $user;
    }

    /**
     * @inheritdoc
     * @return User|null|object
     */
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email','=', $email)
            ->first()
        ;
    }
}
