<?php

namespace Viloveul\Cache\Adapter;

use Exception;
use Redis as RedisClient;
use Viloveul\Cache\Contracts\Adapter as IAdapter;

class RedisAdapter implements IAdapter
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
     * @var mixed
     */
    protected $redisClient;

    /**
     * @param $host
     * @param $port
     */
    public function __construct($host = '127.0.0.1', $port = '6379')
    {
        $this->redisClient = new RedisClient();
        $this->redisClient->connect($host, $port);
    }

    /**
     * @return mixed
     */
    public function clear()
    {
        $results = [];
        try {
            $it = null;
            while ($keys = $this->redisClient->scan($it)) {
                foreach ($keys as $key) {
                    if (strpos($key, $this->getPrefix()) !== 0) {
                        continue;
                    }
                    if ($this->redisClient->delete($key)) {
                        $results[substr($key, strlen($this->getPrefix()))] = 'Deleted Ok';
                    }
                }
            }
            return $results;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $key
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }
        $data = $this->redisClient->get($this->getPrefix() . $key);
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
        return $this->redisClient->exists($this->getPrefix() . $key);
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        if (!$this->has($key)) {
            return null;
        }
        $this->redisClient->delete($this->getPrefix() . $key);
    }

    /**
     * @param $key
     */
    public function set($key, $value, $expire = null)
    {
        $data = serialize($value);
        return $this->redisClient->set($this->getPrefix() . $key, $data, abs($expire ?: $this->getDefaultLifeTime()));
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
