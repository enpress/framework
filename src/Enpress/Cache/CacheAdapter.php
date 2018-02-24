<?php

namespace Enpress\Cache;

use Illuminate\Container\Container;

/**
 * Class CacheAdapter
 * @package Enpress\Cache
 */
class CacheAdapter
{
    /**
     * App Container
     * @var Container
     */
    private $app;

    /**
     * Has the adapter been booted
     * @var bool
     */
    private static $booted;

    /**
     * Non persistent cache store
     * @var array
     */
    private static $cache;

    /**
     * Prefix important for shared cache stores
     * @var string
     */
    private static $cachePrefix = '';

    /**
     * Should CMS data caching be persistent
     * @var bool
     */
    private static $persistent;

    /**
     * Non-persistent groups
     * @var array
     */
    private static $nonPersistentGroups = [];

    /**
     * Default expiration time in minutes
     * @var integer
     */
    private static $defaultExpiration;

    /**
     * CacheAdapter constructor
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        if (!static::$booted) {
            $this->boot();
        }

        return $this;
    }

    /**
     * Set caching configurations and initialises the non-persistent cache store
     */
    protected function boot()
    {
        static::$cache = [];

        $config = $this->app['config'];
        static::$cachePrefix = $config->get('cms.object_cache.prefix', 'wp');
        static::$persistent = $config->get('cms.object_cache.persistent', false);
        static::$defaultExpiration = $config->get('cms.object_cache.expiration', 0);

        static::$booted = true;
    }

    /**
     * Add data to cache
     *
     * @param string $key
     * @param mixed $data
     * @param string $group
     * @param int $expire
     * @return bool
     */
    public function add($key, $data, $group = 'default', int $expire)
    {
        if (function_exists('wp_suspend_cache_addition') && wp_suspend_cache_addition()){
            return false;
        }

        if (empty($group )){
            $group = 'default';
        }

        if ($this->exists($key, $group)){
            return false;
        }

        return $this->set($key, $data, $group, $expire);
    }

    /**
     * Decrement numeric data stored in cache
     *
     * @param string $key
     * @param integer $offset
     * @param string $group
     * @return bool|int|string
     */
    public function decrement($key, $offset = 1, $group = null)
    {
        if (!$this->exists($key, $group)) {
            return false;
        }

        $previous = $this->get($key, $group);

        $new = !is_numeric($previous) || !$previous ? 0 : $previous - $offset;
        $this->set($key, $new, $group);

        return $new;
    }

    /**
     * Remove data from cache
     *
     * @param string $key
     * @param string $group
     * @return bool
     */
    public function delete($key, $group)
    {
        $reference = $this->cacheReference($key, $group);

        if (static::$persistent && !in_array($group, static::$nonPersistentGroups)) {
            $this->app['cache']->forget($reference);
        }

        unset(static::$cache[$reference]);

        return true;
    }

    /**
     * Checks whether the given data is existent in the cache
     *
     * @param string $key
     * @param null $group
     * @return bool
     */
    protected function exists($key, $group = null)
    {
        $reference = $this->cacheReference($key, $group);

        if (static::$persistent && !in_array($group, static::$nonPersistentGroups)) {
            return $this->app['cache']->has($reference);
        }

        return isset(static::$cache[$reference]);
    }

    /**
     * Flush the cache and the persistent cache if configured to do so
     *
     * @return bool
     */
    public function flush()
    {
        static::$cache = [];
        $canFlush = $this->app['config']->get('cms.object_cache.allow_persistent_flush');
        return static::$persistent && $canFlush ? $this->app['cache']->flush() : true;
    }

    /**
     * Retrieve cached data
     *
     * @param string $key
     * @param null $group
     * @param bool $force
     * @param null $found
     * @return bool
     */
    public function get($key, $group = null, $force = false, &$found = null)
    {
        if (!$this->exists($key, $group)) {
            $found = false;
            return false;
        }

        $reference = $this->cacheReference($key, $group);
        $found = true;

        if (isset(static::$cache[$reference])) {
            return is_object(static::$cache[$reference])
                ? clone static::$cache[$reference]
                : static::$cache[$reference];
        }

        $cached = $this->app['cache']->get($reference);
        static::$cache[$reference] = $cached;

        return $cached;
    }

    /**
     * Increment numeric data stored in cache
     *
     * @param string $key
     * @param int $offset
     * @param null $group
     * @return bool | int
     */
    public function increment($key, $offset = 1, $group = null)
    {

        if (!$this->exists($key, $group)) {
            return false;
        }

        $previous = $this->get($key, $group);

        if (!is_numeric($previous)) {
            $previous = 0;
        }

        $new = $previous < 0 ? 0 : $previous + $offset;
        $this->set($key, $new, $group);

        return $new;
    }

    /**
     * Replace previously cached data
     *
     * @param string $key
     * @param mixed $data
     * @param null $group
     * @param integer $expire
     * @return bool
     */
    public function replace($key, $data, $group = null, $expire = 0)
    {
        if ( !$this->exists($key, $group)) {
            return false;
        }

        return $this->set( $key, $data, $group, (int) $expire);
    }


    /**
     * Store data in cache
     *
     * @param string $key
     * @param mixed $data
     * @param null $group
     * @param null $expire
     * @return bool
     */
    public function set($key, $data, $group = null, $expire = null)
    {

        if (is_object($data)) {
            $data = clone $data;
        }

        if (empty($expire)) {
            $expire = static::$defaultExpiration;
        }

        $reference = $this->cacheReference($key, $group);
        
        if (static::$persistent && !in_array($group, static::$nonPersistentGroups)) {
            $this->app['cache']->put($reference, $data, $expire);
        }

        if (is_object( $data )) {
            $data = clone $data;
        }
        
        static::$cache[$reference] = $data;
        return true;
    }

    /**
     * Get the cache reference
     *
     * @param $key
     * @param string $group
     * @return string
     */
    private function cacheReference($key, $group = 'default') {
        if (empty( $group )) {
            $group = 'default';
        }

        return static::$cachePrefix . $group . '_' . $key;
    }

    /**
     * Add to list of groups excluded from persistent cache
     *
     * @param string | array $groups
     */
    public function preventPersistence($groups)
    {
        if (!is_array($groups)) {
            $groups = [$groups];
        }

        if (!empty($groups)) {
            static::$nonPersistentGroups = array_merge(static::$nonPersistentGroups, $groups);
        }
    }

    /**
     * Die and dump all cache contents
     */
    public function dd()
    {
        return dd(static::$cache);
    }

}