<?php

namespace Enpress\Hook\Config;

class Sidebars extends Configurator
{

    /**
     * Register Sidebars
     *
     * @throws ConfigException
     */
    public function apply()
    {
        foreach ($this->configuration as $id => $properties) {

            // Validate Properties
            if(is_numeric($id) || !is_array($properties) || empty($properties) || sizeof($properties) > 2 ){
                throw new ConfigException('Sidebar Configuration Property Mismatch');
            }

            // Ensure ID is safe
            $id = str_replace(' ', '-', $id);
            $id = preg_replace('/[^A-Za-z0-9\-]/', '', $id);

            $overrides = isset($properties[1]) ? $properties[1] : [];

            if(!is_array($overrides)){
                throw new ConfigException('Sidebar Configuration Property Mismatch');
            }

            $defaultArguments = [
                'name'          => $properties[0],
                'id'            => $id,
                'description'   => '',
                'class'         => '',
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '',
                'after_title'   => ''
            ];

            register_sidebar( array_merge($defaultArguments, $overrides) );
        }
    }

}