<?php

if (! function_exists('getWordpressPrefix')) {

    /**
     * Get the database prefix for Wordpress
     *
     * @return string
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    function getWordpressPrefix() {
        $config = app('config');
        $globalPrefix = $config->get('database.connections.mysql.prefix');
        $cmsPrefix = $config->get('cms.db_prefix');

        return $globalPrefix . $cmsPrefix;
    }

}

if (! function_exists('___')) {
    /**
     * Translate the given message.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string  $locale
     * @return string|array|null
     */
    function ___($key, $replace = [], $locale = null)
    {
        return app('translator')->getFromJson($key, $replace, $locale);
    }
}