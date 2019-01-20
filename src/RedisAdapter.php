<?php

namespace Viloveul\Cache;

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
     * @param string $host
     * @param int    $port
     */
    public function __construct(string $host = '127.0.0.1', int $port = 6379)
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
     * @param string $key
     */
    public function delete(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }
        $this->redisClient->delete($this->getPrefix() . $key);
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
        $data = $this->redisClient->get($this->getPrefix() . $key);
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
     * @param  string  $key
     * @return mixed
     */
    public function has(string $key): bool
    {
        return $this->redisClient->exists($this->getPrefix() . $key);
    }

    /**
     * @param  string   $key
     * @param  $value
     * @param  int      $expire
     * @return mixed
     */
    public function set(string $key, $value, int $expire = null)
    {
        $data = serialize($value);
        return $this->redisClient->set($this->getPrefix() . $key, $data, abs($expire ?: $this->getDefaultLifeTime()));
    }

    /**
     * @param int $ttl
     */
    public function setDefaultLifeTime(int $ttl)
    {
        $this->defaultTtl = $ttl;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }
}
