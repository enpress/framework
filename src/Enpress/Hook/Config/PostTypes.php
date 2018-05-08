<?php

namespace Enpress\Hook\Config;
use Doctrine\Common\Inflector\Inflector;

class PostTypes extends Configurator
{

    /**
     * Register Custom Post Types
     *
     * @param $key
     * @param $configuration
     * @throws ConfigException
     */
    protected function set($key, $configuration)
    {

        // Validate Properties
        if(!is_array($configuration) || empty($configuration) || sizeof($configuration) > 2){
            throw new ConfigException('PostType Configuration Property Mismatch');
        }

        $postType = $key;
        $name = $configuration[0];
        $overrides = isset($configuration[1]) ? $configuration[1] : [];

        // Validate Values
        if(!is_string($postType) || is_numeric($postType) || !is_string($name) || is_numeric($name) || !is_array($overrides)){
            throw new ConfigException('PostType Configuration Property Mismatch');
        }

        $pluralName = Inflector::pluralize($name);
        $singularName = Inflector::singularize($name);

        $defaultArguments = [
            'labels'             => [
                'name'               => $pluralName,
                'singular_name'      => $singularName,
                'menu_name'          => $pluralName,
                'name_admin_bar'     => $singularName,
                'add_new'            => 'Add New',
                'add_new_item'       => 'Add New ' . $singularName,
                'new_item'           => 'New ' . $singularName,
                'edit_item'          => 'Edit ' . $singularName,
                'view_item'          => 'View ' . $singularName,
                'all_items'          => 'All ' . $pluralName,
                'search_items'       => 'Search ' . $pluralName,
                'parent_item_colon'  => 'Parent ' . $pluralName,
                'not_found'          => 'No ' . $pluralName . ' Found',
                'not_found_in_trash' => 'No ' . strtolower($pluralName) . ' found in Trash',
            ],
            'description'        => '',
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => [
                'with_front' => false,
                'slug' => $postType
            ],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor'],
        ];

        register_post_type($postType, array_merge ($defaultArguments, $overrides));
    }

}