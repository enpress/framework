<?php

namespace Enpress\Hook\Config;

class Menus extends Configurator
{

    /**
     * Register Custom Menus
     *
     * @throws ConfigException
     */
    public function apply()
    {
        //Validate Configuration
        foreach ($this->configuration as $key => $name) {
            if(is_numeric($key) || !is_string($name) || is_numeric($name)) {
                throw new ConfigException('Menu Configuration Property Mismatch');
            }
        }

        register_nav_menus($this->configuration);
    }

}