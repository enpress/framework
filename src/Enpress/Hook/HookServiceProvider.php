<?php

namespace Enpress\Hook;

use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /*
     * Bind Hook into Application
     */
    public function register()
    {
        $this->app->bind('hook', Hook::class);
    }
}