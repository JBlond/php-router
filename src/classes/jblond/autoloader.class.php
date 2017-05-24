<?php
namespace jblond;

/**
 * Class autoloader
 * @package jblond
 */
class autoloader {

	/**
	 * autoloader constructor.
	 */
	public function __construct(){
		spl_autoload_register(array($this, '__autoload'));
	}

	/**
	 * @param string $class
	 */
	public function __autoload( $class){
		$class = str_replace('\\', '/', $class); // revert path for old PHP on Linux
		if(file_exists('classes/' . $class . '.class.php')){
			require 'classes/' . $class . '.class.php';
		}
	}

}
