<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //user
        $this->app->bind(
          'App\Http\Controllers\Interfaces\User\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\AuthRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\HomeRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\HomeRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\OrdersRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\OrdersRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\TripRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\TripRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\UserRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\UserRepository'
        );

        //delegate
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Delegate\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\Delegate\AuthRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Delegate\DelegateRepositoryInterface',
            'App\Http\Controllers\Eloquent\Delegate\DelegateRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Delegate\HomeRepositoryInterface',
            'App\Http\Controllers\Eloquent\Delegate\HomeRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Delegate\OrdersRepositoryInterface',
            'App\Http\Controllers\Eloquent\Delegate\OrdersRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Delegate\TripRepositoryInterface',
            'App\Http\Controllers\Eloquent\Delegate\TripRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Delegate\UserRepositoryInterface',
            'App\Http\Controllers\Eloquent\Delegate\UserRepository'
        );

        //captin
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Captin\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\Captin\AuthRepository'
        );

        $this->app->bind(
            'App\Http\Controllers\Interfaces\Captin\TripRepositoryInterface',
            'App\Http\Controllers\Eloquent\Captin\TripRepository'
        );



        //admin
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\HomeRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\HomeRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\UserRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\UserRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\OrderRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\OrderRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\CategoryRepository'
        );

        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\AdminRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\AdminRepository'
        );

        //App
        $this->app->bind(
            'App\Http\Controllers\Interfaces\App\AppRepositoryInterface',
            'App\Http\Controllers\Eloquent\App\AppRepository'
        );


        /////////////////////////////////////////
        //userUber
        $this->app->bind(
            'App\Http\Controllers\Interfaces\UserUber\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\UserUber\AuthRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\UserUber\HomeRepositoryInterface',
            'App\Http\Controllers\Eloquent\UserUber\HomeRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\UserUber\TripRepositoryInterface',
            'App\Http\Controllers\Eloquent\UserUber\TripRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\UserUber\UserRepositoryInterface',
            'App\Http\Controllers\Eloquent\UserUber\UserRepository'
        );

        //captin
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Captin\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\Captin\AuthRepository'
        );

        $this->app->bind(
            'App\Http\Controllers\Interfaces\Captin\TripRepositoryInterface',
            'App\Http\Controllers\Eloquent\Captin\TripRepository'
        );

        //appUber
        $this->app->bind(
            'App\Http\Controllers\Interfaces\AppUber\AppRepositoryInterface',
            'App\Http\Controllers\Eloquent\AppUber\AppRepository'
        );
    }
}
