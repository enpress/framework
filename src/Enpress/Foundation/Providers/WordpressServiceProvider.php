<?php

namespace Enpress\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class WordpressServiceProvider extends ServiceProvider {

    /**
     * Set Wordpress configuration constants
     */
    public function boot() {

        $configInstance = $this->app['config'];

        // Get Configurations
        $database = $configInstance->get('database.connections.mysql');
        $cms = $configInstance->get('cms');

        // Set Database Constants
        define('DB_NAME', $database['database']);
        define('DB_USER', $database['username']);
        define('DB_PASSWORD', $database['password']);
        define('DB_HOST', $database['host'] . ':' . $database['port']);

        define('DB_CHARSET', $database['charset']);
        define('DB_COLLATE', $database['collation']);

        // Set Locations
        define('WP_HOME', $this->app['config']->get('app.url'));
        define('WP_SITEURL', $this->app['config']->get('app.url') . '/' . $cms['directory']);

        // Set Debug
        $debugMode = $configInstance->get('app.debug');

        define('JETPACK_DEV_DEBUG', $debugMode);
        define('SAVEQUERIES', $debugMode);
        define('WP_DEBUG', $debugMode);
        define('WP_DEBUG_DISPLAY', $debugMode);
        define('SCRIPT_DEBUG', $debugMode);

        // Configure Wordpress Authentication Keys and Salts
        define('AUTH_KEY',         $cms['auth_key']);
        define('SECURE_AUTH_KEY',  $cms['secure_auth_key']);
        define('LOGGED_IN_KEY',    $cms['logged_in_key']);
        define('NONCE_KEY',        $cms['nonce_key']);
        define('AUTH_SALT',        $cms['auth_salt']);
        define('SECURE_AUTH_SALT', $cms['secure_auth_salt']);
        define('LOGGED_IN_SALT',   $cms['logged_in_salt']);
        define('NONCE_SALT',       $cms['nonce_salt']);

        // Disable Wordpress automatic updating
        define('WP_AUTO_UPDATE_CORE', false);

        // Disable code editor in administration
        define('DISALLOW_FILE_EDIT', true);

        // Disable theme handling
        if (!defined('WP_USE_THEMES')) {
            define('WP_USE_THEMES', false);
        }

        // Configure Wordpress Paths
        $publicPath = $this->app->publicPath();

        define('CONTENT_DIR', $cms['content_directory']);
        define('WP_CONTENT_DIR', $publicPath . ($cms['content_directory'] ? DIRECTORY_SEPARATOR . $cms['content_directory'] : ''));
        define('WP_CONTENT_URL', WP_HOME . ($cms['content_directory'] ? '/' . $cms['content_directory'] : ''));

        // Register Foundation Wordpress Functions
        $this->app['hook']->addFunctionsPath(dirname(__DIR__) . '/functions');

    }

    public function register() {

        //

    }
}
