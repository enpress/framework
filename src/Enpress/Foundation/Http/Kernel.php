<?php

namespace Enpress\Foundation\Http;

use Illuminate\Foundation\Http\Kernel as IlluminateKernel;
use Illuminate\Support\Facades\Facade;

class Kernel extends IlluminateKernel
{
    /**
     * Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Initialize an incoming HTTP request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function initialize($request) {
        $this->app->instance('request', $request);
        $this->request = $request;

        Facade::clearResolvedInstance('request');

        $this->bootstrap();
    }

    /**
     * Handle Stored incoming HTTP request.
     * @return \Illuminate\Http\Response
     */
    public function handleStored() {
        return $this->handle($this->request);
    }

    /**
     * Call the terminate method with stored request
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function terminateStored($response)
    {
        $request = $this->request;
        $this->terminate($request, $response);
    }

}
