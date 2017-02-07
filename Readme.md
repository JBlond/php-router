PHP router
======================

[![Code Climate](https://codeclimate.com/github/JBlond/php-router/badges/gpa.svg)](https://codeclimate.com/github/JBlond/php-router) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/a89005f98a484c2db2baa832c5bd573b)](https://www.codacy.com/app/leet31337/php-router?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JBlond/php-router&amp;utm_campaign=Badge_Grade)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/9dcb9412-a54b-491d-afc1-072b97cc4ecc/mini.png)](https://insight.sensiolabs.com/projects/9dcb9412-a54b-491d-afc1-072b97cc4ecc)

A simple php router class

Examples

```PHP
require 'jblond/autoloader.class.php';
new \jblond\autoloader();
$router = new \jblond\router\router();
$router->registry->set('basepath', '');
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

$router->add('/closure', function () use ($class_object) {
    // $class_object->...
    echo 'closure';
});

$router->add('/user/(.*)/edit', function ($id) {
    echo 'Edit user with id ' . $id;
});

$router->add('/post/', function () {
    require 'post.html';
});

$router->add('/post/reciver/', function () {
    print_r($_POST);
}, 'POST');

$router->add404(function ($url) {
    header("HTTP/1.0 404 Not Found");
    echo '404 :-( ' . $url;
});
$router->run();
```

Apache rewrite config

```
		RewriteEngine on
		RewriteBase /
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.*)$ index.php [QSA]
```
