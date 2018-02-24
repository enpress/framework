<?php

function wp_cache_init() {
    return true;
}

function wp_cache_add( $key, $data, $group = '', $expire = 0 ) {
    return app('cacheadapter')->add($key, $data, $group, (int) $expire);
}

function wp_cache_decr( $key, $offset = 1, $group = '' ) {
    return app('cacheadapter')->decrement($key, $offset, $group);
}

function wp_cache_delete( $key, $group = '' ) {
    return app('cacheadapter')->delete($key, $group);
}

function wp_cache_flush() {
    return app('cacheadapter')->flush();
}

function wp_cache_get( $key, $group = '', $force = false, &$found = null ) {
    return app('cacheadapter')->get($key, $group, $force, $found);
}

function wp_cache_incr( $key, $offset = 1, $group = '' ) {
    return app('cacheadapter')->increment($key, $offset, $group);
}

function wp_cache_replace( $key, $data, $group = '', $expire = 0 ) {
    return app('cacheadapter')->replace($key, $data, $group, (int) $expire);
}

function wp_cache_set( $key, $data, $group = '', $expire = 0 ) {
    return app('cacheadapter')->set($key, $data, $group, (int) $expire);
}

function wp_cache_add_non_persistent_groups($groups) {
    return app('cacheadapter')->preventPersistence($groups);
}

function wp_cache_close() {
    return true;
}