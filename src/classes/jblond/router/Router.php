<?php

namespace jblond\router;

/**
 * Class router
 * @package jblond\router
 */
class Router
{

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
     * route is found
     * @var boolean
     */
    private $route_found;

    /**
     * path
     * @var string
     */
    public $path;

    /**
     * registry object
     * @var Registry
     */
    public $registry;

    /**
     * router constructor.
     */
    public function __construct()
    {
        $this->registry = new Registry();
    }

    /**
     * set base_path
     * @param string $base_path
     */
    public function setBasepath($base_path = '')
    {
        $this->registry->set('basepath', $base_path);
    }

    /**
     * init
     */
    public function init()
    {
        $this->path = '';
        $parsed_url = parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL));
        if (isset($parsed_url['path'])) {
            $this->path = trim($parsed_url['path']);
        }
    }

    /**
     * add route
     * @param string $expression
     * @param mixed $function
     * @param string|array $method default is GET
     */
    public function add($expression, $function, $method = array('GET'))
    {
        array_push($this->routes, array(
            'expression' => $expression,
            'function' => $function,
            'method' => $method
        ));
    }

    /**
     * add a get route
     * @param string $expression
     * @param mixed $function
     */
    public function get($expression, $function)
    {
        $this->add($expression, $function, 'GET');
    }

    /**
     * add a post route
     * @param string $expression
     * @param mixed $function
     */
    public function post($expression, $function)
    {
        $this->add($expression, $function, 'POST');
    }

    /**
     * add 404
     * @param mixed $function
     */
    public function add404($function)
    {
        array_push($this->routes404, $function);
    }

    /**
     * check if the method is allowed
     * @param array $route
     * @return bool
     */
    private function isMethodNotInRoutes($route)
    {
        if (is_array($route['method'])) {
            if (! in_array(filter_input(INPUT_SERVER, 'REQUEST_METHOD'), (array) $route['method'])) {
                return true;
            }
        } elseif (filter_input(INPUT_SERVER, 'REQUEST_METHOD') !== $route['method']) {
            return true;
        }
        return false;
    }

    /**
     * run all 404 routes
     */
    private function run404()
    {
        if (!$this->route_found) {
            foreach ($this->routes404 as $route404) {
                call_user_func_array($route404, array($this->path));
            }
        }
    }

    /**
     * @param string $expression
     * @return mixed
     */
    private function replaceLambdaPatterns($expression)
    {
        if (strpos($expression, ':') !== false) {
            return str_replace(
                array(':any', ':num', ':all', ':an', ':url', ':hex'),
                array('[^/]+', '[0-9]+', '.*', '[0-9A-Za-z]+', '[0-9A-Za-z-_]+', '[0-9A-Fa-f]+'),
                $expression
            );
        }
        return $expression;
    }

    /**
     * @param array $route
     * @return mixed
     */
    private function prepareRoute($route)
    {
        if ($this->registry->get('basepath')) {
            $route['expression'] = '(' . $this->registry->get('basepath') . ')/' . $route['expression'];
        }

        //try to find lambda patterns
        $route['expression'] = $this->replaceLambdaPatterns($route['expression']);

        //Add 'find string start' automatically
        $route['expression'] = '^' . $route['expression'];

        //Add 'find string end' automatically
        $route['expression'] = $route['expression'] . '$';

        return $route;
    }

    /**
     * run
     */
    public function run()
    {
        $this->route_found = false;

        foreach ($this->routes as $route) {
            if ($this->isMethodNotInRoutes($route)) {
                // skip this route
                continue;
            }

            $route = $this->prepareRoute($route);
            //check match
            if (preg_match('#' . $route['expression'] . '#', urldecode($this->path), $matches)) {
                array_shift($matches); //Always remove first element. This contains the whole string

                if ($this->registry->get('basepath')) {
                    array_shift($matches);//Remove Base path
                }

                if (is_callable($route['function'])) {
                    call_user_func_array($route['function'], $matches);
                }
                $this->route_found = true;
                // we are done here stop the loop
                break;
            }
        }
        $this->run404();
    }
}
