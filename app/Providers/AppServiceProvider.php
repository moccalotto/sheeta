<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $app->singleton(MongoDB\Client::class, function ($app) {
            return new MongoDB\Client(config('mongo.dsn'));
        });

        $app->singleton(MongoDB\Database::class, function($app) {
            $client = $app->make(MongoDB\Client::class);
            return $client->selectDatabase(config('mongo.database'));
        });
    }
}
