<?php

namespace Enpress\Cache;

use Illuminate\Container\Container;

class CacheAdapter
{
    /**
     * App Container
     * @var Container
     */
    private $app;

    /**
     * Non persistent cache store
     * @var array
     */
    private $cache;

    /**
     * Prefix important for shared cache stores
     * @var string
     */
    private $cachePrefix = '';

    /**
     * Should CMS data caching be persistent
     * @var bool
     */
    private $persistent;

    /**
     * Non-persistent groups
     * @var array
     */
    private $nonPersistentGroups = [];

    /**
     * Default expiration time in minutes
     * @var integer
     */
    private $defaultExpiration;

    /**
     * CacheAdapter constructor
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->cache = [];

        $config = $this->app['config'];
        $this->cachePrefix = $config->get('cms.object_cache.prefix', 'wp');
        $this->persistent = $config->get('cms.object_cache.persistent', false);
        $this->defaultExpiration = $config->get('cms.object_cache.expiration', 0);

        return $this;
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

        if ($this->persistent && !in_array($group, $this->nonPersistentGroups)) {
            $this->app['cache']->forget($reference);
        }

        unset($this->cache[$reference]);

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

        if ($this->persistent && !in_array($group, $this->nonPersistentGroups)) {
            return $this->app['cache']->has($reference);
        }

        return isset($this->cache[$reference]);
    }

    /**
     * Flush the cache and the persistent cache if configured to do so
     *
     * @return bool
     */
    public function flush()
    {
        $this->cache = [];
        $canFlush = $this->app['config']->get('cms.object_cache.allow_persistent_flush');
        return $this->persistent && $canFlush ? $this->app['cache']->flush() : true;
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

        if (isset($this->cache[$reference])) {
            return is_object($this->cache[$reference])
                ? clone $this->cache[$reference]
                : $this->cache[$reference];
        }

        $cached = $this->app['cache']->get($reference);
        $this->cache[$reference] = $cached;

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
            $expire = $this->defaultExpiration;
        }

        $reference = $this->cacheReference($key, $group);

        if ($this->persistent && !in_array($group, $this->nonPersistentGroups)) {
            $this->app['cache']->put($reference, $data, $expire);
        }

        if (is_object( $data )) {
            $data = clone $data;
        }

        $this->cache[$reference] = $data;
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

        return $this->cachePrefix . $group . '_' . $key;
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
            $this->nonPersistentGroups = array_merge($this->nonPersistentGroups, $groups);
        }
    }

    /**
     * Die and dump all cache contents
     */
    public function dd()
    {
        return dd($this->cache);
    }

}