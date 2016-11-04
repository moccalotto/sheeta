<?php

namespace App\Providers;

use MongoDB\Client;
use MongoDB\Database;
use Illuminate\Support\ServiceProvider;

class MongoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config'];
            return new Client(
                $config->get('mongodb.uri'),
                $config->get('mongodb.options', []),
                $config->get('mongodb.driverOptions', [])
            );
        });

        $this->app->singleton(Database::class, function ($app) {
            $db = $app['config']->get('mongodb.db');
            return $app->make(Client::class)->$db;
        });

        $this->app->bind('mongo', Database::class);
    }
}
