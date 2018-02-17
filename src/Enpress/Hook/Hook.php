<?php

namespace Enpress\Hook;

use Enpress\Hook\Config\Images;
use Enpress\Hook\Config\Menus;
use Enpress\Hook\Config\PostTypes;
use Enpress\Hook\Config\Sidebars;
use Enpress\Hook\Config\Taxonomies;
use Enpress\Hook\Config\Templates;
use Illuminate\Foundation\Application;

class Hook
{

    /*
     * Functions path receptacle
     */
    protected static $functionsPaths = [];

    protected $configurationPath;
    protected $configurationInstances = [];

    /**
     * Register default path
     *
     * @param Application $container
     */
    public function __construct(Application $container)
    {
        $this->configurationPath = $container->configPath('cms');
        $config = $container['config'];


        $this->configurationInstances = [
            new Taxonomies($config->get('taxonomies')),
            new PostTypes($config->get('post-types')),
            new Menus($config->get('menus')),
            new Templates($config->get('templates')),
            new Images($config->get('images')),
            new Sidebars($config->get('sidebars'))
        ];
    }

    /**
     * Perform Initialization of functions and must use plugins
     */
    public function initialize()
    {
        $this->includeFunctions();
        $this->includeMustUsePlugins();
        $this->performConfigurations();
    }

    /**
     * Get all functions paths
     *
     * @return array
     */
    public function functionsPaths()
    {
        return static::$functionsPaths;
    }

    /**
     * Add absolute functions path
     *
     * @param string $path
     */
    public function addFunctionPath(string $path)
    {
        static::$functionsPaths[] = $path;
    }

    /**
     * Perform functions inclusion
     */
    protected function includeFunctions()
    {

        $paths = $this->functionsPaths();

        foreach ($paths as $path) {
            $contents = new \DirectoryIterator($path);
            $files = [];

            foreach ($contents as $file) {
                if ($file->isDot()) { continue; }
                if ($file->isDir()) { continue; }
                if ($file->getExtension() != 'php') { continue; }

                $files[] = $path . DIRECTORY_SEPARATOR . $file->getBasename();
            }

            foreach ($files as $file) {
                include_once $file;
            }
        }
    }

    /**
     * Perform inclusion of all plugins within the Must Use directory
     */
    protected function includeMustUsePlugins()
    {
        $directory = WPMU_PLUGIN_DIR;

        foreach (new \DirectoryIterator($directory) as $contents) {
            if ($contents->isDot()) { continue; }
            if (!$contents->isDir()) { continue; }

            foreach (new \DirectoryIterator($contents->getPathname()) as $file) {
                if ($file->isDot()) { continue; }
                if ($file->isDir()) { continue; }
                if ($file->getExtension() != 'php') { continue; }
                
                $file_contents = file_get_contents($file->getPathname());
                if (stripos($file_contents,'Plugin Name') === -1) { continue; }

                include_once($file->getPathname());
            }
        }
    }

    protected function performConfigurations()
    {
        foreach ($this->configurationInstances as $instance) {
            $instance->apply();
        }
    }
}