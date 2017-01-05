PHP router
======================

A simple php router class

Examples

```PHP
$router = new \jblond\router\router();
$router->registry->set('basepath', '');
$router->init();

$router->add('', function () {
    echo 'Welcome';
});

$router->add('info/', function () {
    phpinfo();
});

$router->add('test.html', function () {
    echo 'test.html Welcome';
});

$router->add('closure', function () use ($class_object) {
    // $class_object->...
    echo 'closure';
});

$router->add('user/(.*)/edit', function ($id) {
    echo 'Edit user with id ' . $id;
});

$router->add('post/', function () {
    require 'post.html';
});

$router->add('post/reciver/', function () {
    print_r($_POST);
}, 'POST');

$router->add404(function ($url) {
    header("HTTP/1.0 404 Not Found");
    echo '404 :-( ' . $url;
});
$router->run();
```