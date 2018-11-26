<?php

namespace Viloveul\Cache;

use Viloveul\Cache\Contracts\Adapter as ICacheAdapter;
use Viloveul\Cache\Contracts\Cache as ICache;

class Cache implements ICache
{
    /**
     * @var mixed
     */
    protected $adapter;

    /**
     * @param ICacheAdapter $adapter
     */
    public function __construct(ICacheAdapter $adapter)
    {
        $this->setAdapter($adapter);
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        return $this->adapter->clear();
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function delete($key)
    {
        return $this->adapter->delete($key);
    }

    /**
     * @param $keys
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    /**
     * @param  $key
     * @param  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->adapter->get($key) ?: $default;
    }

    /**
     * @return mixed
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param  $keys
     * @param  $default
     * @return mixed
     */
    public function getMultiple($keys, $default = null)
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = $this->get($key, $default);
        }
        return $results;
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function has($key)
    {
        return $this->adapter->has($key);
    }

    /**
     * @param  $key
     * @param  $value
     * @param  $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->adapter->set($key, $value, $ttl ?: $this->adapter->getDefaultLifeTime());
    }

    /**
     * @param ICacheAdapter $adapter
     */
    public function setAdapter(ICacheAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param $values
     * @param $ttl
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }
}
