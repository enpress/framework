<?php

namespace Enpress\Foundation\Providers;

use Enpress\Foundation\Console\SaltsGenerateCommand;
use Illuminate\Support\ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider {

    /**
     * Register Artisan Commands
     */
    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SaltsGenerateCommand::class,
            ]);
        }
    }

    public function register() {
        //
    }

}