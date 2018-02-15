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