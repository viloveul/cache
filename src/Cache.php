<?php

namespace Viloveul\Cache;

use Viloveul\Cache\AdapterException;
use Viloveul\Cache\Collection;
use Viloveul\Cache\Contracts\Adapter as ICacheAdapter;
use Viloveul\Cache\Contracts\Cache as ICache;

class Cache implements ICache
{
    /**
     * @var mixed
     */
    protected $adapter;

    /**
     * @var array
     */
    protected $tmp = [];

    /**
     * @param ICacheAdapter $adapter
     */
    public function __construct(ICacheAdapter $adapter)
    {
        $this->setAdapter($adapter);
        $this->tmp = new Collection();
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        $this->tmp->clear();
        return $this->getAdapter()->clear();
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function delete($key)
    {
        $this->tmp->delete($key);
        return $this->getAdapter()->delete($key);
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
        if (!$this->tmp->has($key)) {
            $this->tmp->set($key, $this->getAdapter()->get($key, $default));
        }
        return $this->tmp->get($key, $default);
    }

    /**
     * @return mixed
     */
    public function getAdapter(): ICacheAdapter
    {
        if (!($this->adapter instanceof ICacheAdapter)) {
            throw new AdapterException("Cache Adapter is not valid.");
        }
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
        return $this->getAdapter()->has($key);
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * @param  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param $key
     */
    public function offsetUnset($key)
    {
        $this->delete($key);
    }

    /**
     * @param  $key
     * @param  $value
     * @param  $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        $res = $this->getAdapter()->set($key, $value, $ttl ?: $this->getAdapter()->getDefaultLifeTime());
        $this->tmp->set($key, $value);
        return $res;
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
