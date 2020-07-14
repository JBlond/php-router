PHP router
======================

[![Code Climate](https://codeclimate.com/github/JBlond/php-router/badges/gpa.svg)](https://codeclimate.com/github/JBlond/php-router) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/a89005f98a484c2db2baa832c5bd573b)](https://www.codacy.com/app/leet31337/php-router?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JBlond/php-router&amp;utm_campaign=Badge_Grade)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/9dcb9412-a54b-491d-afc1-072b97cc4ecc/mini.png)](https://insight.sensiolabs.com/projects/9dcb9412-a54b-491d-afc1-072b97cc4ecc)

## A simple php router class

Less than 300 lines of code with comments

Supports  
- lambda URLs: 
	- **:any** Any sign
	- **:num** Only numbers
	- **:all** All characters
	- **:an** A-Z a-z 0-9
	- **:url** A-Z a-z 0-9 - _ 
	- **:hex** hexadecimal 
- Regex URLs e.g. /user/(.*)/edit
- Optional Subroutes
- Works in Subdirs, if you use `->set_basepath('/yoursubdir')` 
- Custom response headers
	- download
	- header(s)
	- redirect
	- 503 error

## Install

```
composer require jblond/php-router
```

## Examples

### Static routes
```PHP
require 'jblond/Autoloader.php';
new \jblond\Autoloader();
$router = new \jblond\router\Router();
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
$router->add('/post/', function () {
	require 'post.html';
});

$router->post('/post/reciver/', function () {
    print_r($_POST);
});
```

### dynamic routes
```PHP	
$router->add('/user/(.*)/edit', function ($id) {
    echo 'Edit user with id ' . $id;
});

$router->get('/test/(:any)', function () {
    print_r(filter_input(INPUT_SERVER, 'REQUEST_URI'));
});
	
```

### Integration with other libraries aka using closure
```PHP
$tpl = new \Acme\Template\Template();
$router->add('/closure', function () use ($tpl) {
    // $tpl->...
    echo 'closure';
});
```

### Define 404 not found page	
	
```PHP
$router->add404(function ($url) {
    header("HTTP/1.0 404 Not Found");
    echo '404 :-( ' . $url;
});
$router->run();
```

### Responses
```PHP
$router->get('/503/', function (){
    $response = new \jblond\router\Responses();
    $response->error503();
});
```

### Optional Subpatterns
Optional route subpattern can be made of using `?`  aftern the normal pattern.

```PHP
$router->get(
    '/phonebook(\/[A-Za-z]+(\/[A-Za-z]+(\/[A-Za-z]+(\/[0-9-]+)?)?)?)?/',
    function ($lastname = null, $surname = null, $street = null, $number = null) {
        if(!$lastname) {
            echo 'Phonebook all entries';
            return;
        }
        if(!$surname){
            echo 'Phonebook lookup lastname: ' . $lastname;
            return;
        }
        if(!$street){
            echo 'Phonebook lookup lastname: ' . $lastname . ' Surname: ' . $surname;
            return;
        }
        if(!$number){
            echo 'Phonebook lookup lastname: ' . $lastname . ' Surname: ' . $surname . ' Street: ' . $street;
            return;
        }
        echo ' FULL SEARCH';
});
```

For the regexpattern see https://regexper.com/#%2Fphonebook(%5C%2F%5BA-Za-z%5D%2B(%5C%2F%5BA-Za-z%5D%2B(%5C%2F%5BA-Za-z%5D%2B(%5C%2F%5B0-9-%5D%2B)%3F)%3F)%3F)%3F%2F

Also good for testing your regex: http://www.phpliveregex.com/ use preg_match

### Apache rewrite config

```
RewriteEngine on
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```
