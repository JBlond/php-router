<?php

namespace jblond\router;

/**
 * Class registry
 * @package jblond\router
 */
class Registry
{
    /**
     * @var array
     */
    private array $registry = array();

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function set(mixed $key, mixed $value): void
    {
        $this->registry[(string) $key] = $value;
    }

    /**
     * @param mixed $key
     * @return bool|mixed
     */
    public function get(mixed $key): mixed
    {
        if (array_key_exists($key, $this->registry)) {
            return $this->registry[(string) $key];
        }
        return false;
    }
}
