<?php

namespace Enpress\Cache;

use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{

    public function boot()
    {
        /*
         * Prevent persistence of groups declared in config file
         */
        $prevented = config('cms.object_cache.not_persisted', []);
        $this->app['cacheadapter']->preventPersistence($prevented);
    }

    public function register()
    {
        $this->app->singleton('cacheadapter', CacheAdapter::class);
    }

}