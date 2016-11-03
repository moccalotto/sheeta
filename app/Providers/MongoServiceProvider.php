<?php

namespace App\Providers;

use App\Util\Mongo;
use MongoDB\Driver\Manager;
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
        $this->app->singleton(Mongo::class, function ($app) {
            return new Mongo(
                $app->make(Manager::class),
                $app['config']->get('mongodb.db')
            );
        });

        $this->app->singleton(Manager::class, function ($app) {
            $config = $app['config'];
            return new Manager(
                $config->get('mongodb.uri'),
                $config->get('mongodb.options', []),
                $config->get('mongodb.driverOptions', [])
            );
        });

        $this->app->bind('mongo', Mongo::class);
    }
}
