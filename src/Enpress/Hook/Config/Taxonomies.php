<?php

namespace Enpress\Hook\Config;
use Doctrine\Common\Inflector\Inflector;

class Taxonomies extends Configurator
{

    /**
     * Register Custom Taxonomies
     *
     * @param $key
     * @param $configuration
     * @throws ConfigException
     */
    protected function set($key, $configuration)
    {

        // Validate Properties
        if(!is_array($configuration) || sizeof($configuration) < 2 || sizeof($configuration) > 3){
            throw new ConfigException('Taxonomy Configuration Property Mismatch');
        }

        $taxonomy = $key;
        $name = $configuration[0];
        $postType = $configuration[1];
        $overrides = isset($configuration[2]) ? $configuration[2] : [];

        // Validate Values
        if(!is_string($taxonomy) ||
            is_numeric($taxonomy) ||
            !is_string($name) ||
            is_numeric($name) ||
            (!is_string($postType) && !is_array($postType)) ||
            !is_array($overrides)
        ){
            throw new ConfigException('Taxonomy Configuration Property Mismatch');
        }

        $pluralName = Inflector::pluralize($name);
        $singularName = Inflector::singularize($name);

        $defaultArguments = [
        'hierarchical'          => false,
        'labels'                => [
            'name'                       => $pluralName,
            'singular_name'              => $singularName,
            'search_items'               => 'Search ' . $pluralName,
            'popular_items'              => 'Popular ' . $pluralName,
            'all_items'                  => 'All ' . $pluralName,
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => 'Edit ' . $singularName,
            'update_item'                => 'Update ' . $singularName,
            'add_new_item'               => 'Add New ' . $singularName,
            'new_item_name'              => 'New ' . $singularName . ' Name',
            'separate_items_with_commas' => 'Separate ' . strtolower($pluralName) . ' with commas',
            'add_or_remove_items'        => 'Add or remove ' . strtolower($pluralName),
            'choose_from_most_used'      => 'Choose from the most used ' . strtolower($pluralName),
            'not_found'                  => 'No ' . $pluralName . ' found.',
            'menu_name'                  => $pluralName,
        ],
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => ['slug' => $taxonomy]
        ];


        register_taxonomy( $taxonomy, $postType, array_merge($defaultArguments, $overrides) );
    }

}