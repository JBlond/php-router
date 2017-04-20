<?php
namespace jblond\router;

/**
 * Class router
 * @package jblond\router
 */
class router {

	/**
	 * array of routes
	 * @var array
	 */
	public $routes = array();

	/**
	 * array of 404's
	 * @var array
	 */
	public $routes404 = array();

	/**
	 * path
	 * @var string
	 */
	public $path;

	/**
	 * registry object
	 * @var registry
	 */
	public $registry;

	/**
	 * router constructor.
	 */
	public function __construct(){
		$this->registry = new registry();
	}

	/**
	 * init
	 */
	public function init(){
		$parsed_url = parse_url($_SERVER['REQUEST_URI']);
		if(isset($parsed_url['path'])){
			$this->path = trim($parsed_url['path']);
		}
		else
		{
			$this->path = '';
		}
	}

	/**
	 * add route
	 * @param string $expression
	 * @param mixed $function
	 * @param string|array $method default is GET
	 */
	public function add($expression, $function, $method = array('GET')){
		array_push($this->routes, array(
			'expression' => $expression,
			'function' => $function,
			'method' => $method
		));
	}

	/**
	 * add 404
	 * @param mixed $function
	 */
	public function add404($function){
		array_push($this->routes404, $function);
	}

	/**
	 * check if the method is allowed
	 * @param array $route
	 * @return bool
	 */
	private function is_method_in_routes($route){
		if(is_array($route['method'])){
			if(! in_array($_SERVER['REQUEST_METHOD'], (array) $route['method'])){
				return true;
			}
		}
		else
		{
			if($_SERVER['REQUEST_METHOD'] !== $route['method']){
				return true;
			}
		}
		return false;
	}
	/**
	 * run
	 */
	public function run(){
		$route_found = false;

		foreach($this->routes as $route){

			if($this->is_method_in_routes($route)){
				continue;
			}

			if($this->registry->get('basepath')){
				$route['expression'] = '('.$this->registry->get('basepath').')/'.$route['expression'];
			}

			//Add 'find string start' automatically
			$route['expression'] = '^'.$route['expression'];

			//Add 'find string end' automatically
			$route['expression'] = $route['expression'].'$';

			//check match
			if(preg_match('#'.$route['expression'].'#',$this->path,$matches)){
				array_shift($matches); //Always remove first element. This contains the whole string

				if($this->registry->get('basepath')){
					array_shift($matches);//Remove Base path
				}

				call_user_func_array($route['function'], $matches);
				$route_found = true;
			}
		}

		if(!$route_found){
			foreach($this->routes404 as $route404){
				call_user_func_array($route404, array($this->path));
			}
		}
	}
}
