<?php

namespace Enpress\Hook\Config;

class Images extends Configurator
{

    /**
     * Register Custom Images
     *
     * @throws ConfigException
     */
    public function apply()
    {
        foreach ($this->configuration as $name => $properties) {

            // Validate Properties
            if(!is_array($properties) || sizeof($properties) !== 3){
                throw new ConfigException('Image Configuration Property Mismatch');
            }

            add_image_size($name, $properties[0], $properties[1], $properties[2]);
        }
    }

}