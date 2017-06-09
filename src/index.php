<?php
require 'classes/jblond/autoloader.class.php';
new \jblond\autoloader();
$router = new \jblond\router\router();
$router->set_basepath('');
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

$router->post('/post/reciver/', function () {
	// can print_r POST vars
});

$router->get('/503/', function (){
	$response = new \jblond\router\responses();
	$response->error_503();
});

$router->add404(function ($url) {
	header("HTTP/1.0 404 Not Found");
	echo '404 :-( ' . $url;
});
$router->run();
