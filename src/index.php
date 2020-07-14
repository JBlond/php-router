<?php

use jblond\Autoloader;
use jblond\router\Responses;
use jblond\router\Router;

require 'classes/jblond/Autoloader.php';

new Autoloader();
$router = new Router();
$router->setBasepath('');
$router->init();

$router->add('/', function () {
    echo 'Welcome';
});

$router->add('/info/', function () {
    phpinfo();
});

$router->add('/test.html', function () {
    echo 'test.html Welcome';
});

/*
$router->add('/closure', function () use ($class_object) {
    // $class_object->...
    echo 'closure';
});
*/

$router->add('/user/(.*)/edit', function ($id) {
    echo 'Edit user with id ' . $id;
}, array('GET', 'POST'));

$router->get('/test/(:any)/', function () {
    echo (filter_input(INPUT_SERVER, 'REQUEST_URI'));
});

$router->add('/post/', function () {
    require 'post.html';
});

$router->add('/pöst/', function () {
    require 'post.html';
});

$router->post('/post/reciver/', function () {
    print_r($_POST);
});

$router->get('/503/', function () {
    $response = new Responses();
    $response->error503();
});

$router->add404(function ($url) {
    header("HTTP/1.0 404 Not Found");
    echo '404 :-( ' . $url;
});
$router->run();
