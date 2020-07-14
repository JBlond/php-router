<?php
namespace jblond\router;

/**
 * Class registry
 * @package jblond\router
 */
class Registry {

    /**
     * @var array
     */
    private $registry = array();

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $this->registry["$key"] = $value;
    }

    /**
     * @param mixed $key
     * @return bool|mixed
     */
    public function get($key){
        if(array_key_exists($key, $this->registry)) {
            return $this->registry["$key"];
        }
        return false;
    }

}
