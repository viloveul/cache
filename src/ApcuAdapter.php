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
     * @param string $key
     */
    public function delete(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }
        return apcu_delete($this->getPrefix() . $key);
    }

    /**
     * @param string     $key
     * @param $default
     */
    public function get(string $key, $default = null)
    {
        if (!$this->has($key)) {
            return null;
        }
        $data = apcu_fetch($this->getPrefix() . $key);
        return @unserialize($data) ?: ($data ?: $default);
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
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $key
     */
    public function has(string $key): bool
    {
        return apcu_exists($this->getPrefix() . $key);
    }

    /**
     * @param string   $key
     * @param $value
     * @param int      $expire
     */
    public function set(string $key, $value, int $expire = null)
    {
        $data = serialize($value);
        return apcu_store($this->getPrefix() . $key, $data, abs($expire ?: $this->getDefaultLifeTime()));
    }

    /**
     * @param int $ttl
     */
    public function setDefaultLifeTime(int $ttl)
    {
        $this->defaultTtl = $ttl;
    }

    /**
     * @param int $prefix
     */
    public function setPrefix(int $prefix)
    {
        $this->prefix = $prefix;
    }
}
