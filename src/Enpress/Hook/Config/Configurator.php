<?php

namespace Enpress\Hook\Config;

abstract class Configurator
{

    protected $configuration = [];

    /*
     * Set configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /*
     * Add to configuration
     */
    public function add($configuration)
    {
        $this->configuration += $configuration;
    }

    /*
     * Method to set the configuration
     */
    protected function set($key, $configuration)
    {
        //
    }

    /*
     * Iterate over the configuration
     */
    public function apply()
    {
        add_action('init', function() {
            foreach ($this->configuration as $key => $item) {
                $this->set($key, $item);
            }
        });
    }

}