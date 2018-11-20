<?php

namespace Viloveul\Cache;

use Viloveul\Cache\Contracts\Adapter as IAdapter;

class ApcuAdapter implements IAdapter
{
    /**
     * @var int
     */
    protected $defaultTtl = 3600;

    /**
     * @var string
     */
    protected $prefix = 'viloveul';

    /**
     * @param $key
     */
    public function delete($key)
    {
        if (!$this->has($key)) {
            return null;
        }
        return apcu_delete($this->getPrefix() . $key);
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        $results = [];
        if ($cacheInfo = apcu_cache_info()) {
            if (array_key_exists('cache_list', $cacheInfo)) {
                foreach ($cacheInfo['cache_list'] as $cache) {
                    if (strpos($cache['info'], $this->getPrefix()) !== 0) {
                        continue;
                    }
                    $key = substr($cache['info'], strlen($this->getPrefix()));
                    if ($this->delete($key)) {
                        $results[$key] = 'Deleted Ok';
                    }
                }
            }
        } else {
            apcu_clear_cache();
        }
        return $results;
    }

    /**
     * @param $key
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }
        $data = apcu_fetch($this->getPrefix() . $key);
        return @unserialize($data) ?: $data;
    }

    /**
     * @return mixed
     */
    public function getDefaultLifeTime()
    {
        return $this->defaultTtl;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param $key
     */
    public function has($key)
    {
        return apcu_exists($this->getPrefix() . $key);
    }

    /**
     * @param $key
     * @param $value
     * @param $expire
     */
    public function set($key, $value, $expire = null)
    {
        $data = serialize($value);
        return apcu_store($this->getPrefix() . $key, $data, abs($expire ?: $this->getDefaultLifeTime()));
    }

    /**
     * @param $ttl
     */
    public function setDefaultLifeTime($ttl)
    {
        $this->defaultTtl = $ttl;
    }

    /**
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}
