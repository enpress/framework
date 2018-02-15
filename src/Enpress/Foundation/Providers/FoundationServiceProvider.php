<?php

namespace Enpress\Foundation\Providers;

use Enpress\Hook\HookServiceProvider;
use Illuminate\Foundation\Providers\FormRequestServiceProvider;
use Illuminate\Foundation\Providers\FoundationServiceProvider as IlluminateFoundationServiceProvider;

class FoundationServiceProvider extends IlluminateFoundationServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        FormRequestServiceProvider::class,
        WordpressServiceProvider::class,
        HookServiceProvider::class
    ];
}
