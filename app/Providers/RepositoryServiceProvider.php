<?php

namespace App\Providers;

use App\Repositories\Auth\{AuthRepository, AuthRepositoryEloquent};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Repository bindings.
     *
     * @var array
     */
    const BINDINGS = [
        AuthRepository::class => AuthRepositoryEloquent::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach (self::BINDINGS as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
