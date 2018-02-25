<?php

if(! function_exists('wp_cache_init')) {
    function wp_cache_init() {
        return true;
    }
}

if(! function_exists('wp_cache_add')) {
    function wp_cache_add($key, $data, $group = '', $expire = 0) {
        return app('cache.adapter')->add($key, $data, $group, (int)$expire);
    }
}

if(! function_exists('wp_cache_decr')) {
    function wp_cache_decr( $key, $offset = 1, $group = '' ) {
        return app('cache.adapter')->decrement($key, $offset, $group);
    }
}

if(! function_exists('wp_cache_delete')) {
    function wp_cache_delete($key, $group = '') {
        return app('cache.adapter')->delete($key, $group);
    }
}

if(! function_exists('wp_cache_flush')) {
    function wp_cache_flush() {
        return app('cache.adapter')->flush();
    }
}

if(! function_exists('wp_cache_get')) {
    function wp_cache_get($key, $group = '', $force = false, &$found = null) {
        return app('cache.adapter')->get($key, $group, $force, $found);
    }
}

if(! function_exists('wp_cache_incr')) {
    function wp_cache_incr($key, $offset = 1, $group = '') {
        return app('cache.adapter')->increment($key, $offset, $group);
    }
}

if(! function_exists('wp_cache_replace')) {
    function wp_cache_replace($key, $data, $group = '', $expire = 0) {
        return app('cache.adapter')->replace($key, $data, $group, (int)$expire);
    }
}

if(! function_exists('wp_cache_set')) {
    function wp_cache_set($key, $data, $group = '', $expire = 0) {
        return app('cache.adapter')->set($key, $data, $group, (int)$expire);
    }
}

if(! function_exists('wp_cache_add_non_persistent_groups')) {
    function wp_cache_add_non_persistent_groups($groups) {
        return app('cache.adapter')->preventPersistence($groups);
    }
}

if(! function_exists('wp_cache_close')) {
    function wp_cache_close() {
        return true;
    }
}