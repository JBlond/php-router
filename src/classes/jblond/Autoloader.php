<?php

namespace jblond;

/**
 * Class autoloader
 * @package jblond
 */
class Autoloader
{
    /**
     * autoloader constructor.
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * @param string $class
     */
    private function autoload(string $class): void
    {
        $class = str_replace('\\', '/', $class); // revert path for old PHP on Linux
        if (file_exists('classes/' . $class . '.php')) {
            /** @noinspection PhpIncludeInspection */
            require 'classes/' . $class . '.php';
        }
    }
}
