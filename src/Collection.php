<?php

namespace Viloveul\Cache;

use Viloveul\Cache\Contracts\Collection as ICollection;

class Collection implements ICollection
{
    /**
     * @var mixed
     */
    private $collections;

    public function __construct()
    {
        $this->collections = [];
    }

    public function clear(): void
    {
        $this->collections = [];
    }

    /**
     * @param string $key
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($this->collections[$key]);
        }
    }

    /**
     * @param  string     $key
     * @param  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->collections[$key] : $default;
    }

    /**
     * @param string $key
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->collections);
    }

    /**
     * @param string   $key
     * @param $value
     */
    public function set(string $key, $value = null): void
    {
        $this->collections[$key] = $value;
    }
}
