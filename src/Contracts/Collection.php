<?php

namespace Viloveul\Cache\Contracts;

interface Collection
{
    public function clear(): void;

    /**
     * @param string $key
     */
    public function delete(string $key): void;

    /**
     * @param string     $key
     * @param $default
     */
    public function get(string $key, $default = null);

    /**
     * @param string $key
     */
    public function has(string $key): bool;

    /**
     * @param string   $key
     * @param $value
     */
    public function set(string $key, $value = null): void;
}
