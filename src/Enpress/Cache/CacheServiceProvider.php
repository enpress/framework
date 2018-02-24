<?php

namespace Enpress\Cache;

use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $prevented = config('cms.object_cache.not_persisted', []);
        $this->app['cacheadapter']->preventPersistence($prevented);
    }

    public function register()
    {
        $this->app->bind('cacheadapter', CacheAdapter::class);
    }

}