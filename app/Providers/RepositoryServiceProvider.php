<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repository\UserRepositoryInterface::class,
            \App\Repository\UserRepository::class
        );

        $this->app->bind(
            \App\Repository\EntityRepositoryInterface::class,
            \App\Repository\EntityRepository::class
        );

        $this->app->bind(
            \App\Repository\EntityFileRepositoryInterface::class,
            \App\Repository\EntityFileRepository::class
        );
    }
}
