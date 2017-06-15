PHP router
======================

[![Code Climate](https://codeclimate.com/github/JBlond/php-router/badges/gpa.svg)](https://codeclimate.com/github/JBlond/php-router) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/a89005f98a484c2db2baa832c5bd573b)](https://www.codacy.com/app/leet31337/php-router?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JBlond/php-router&amp;utm_campaign=Badge_Grade)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/9dcb9412-a54b-491d-afc1-072b97cc4ecc/mini.png)](https://insight.sensiolabs.com/projects/9dcb9412-a54b-491d-afc1-072b97cc4ecc)

## A simple php router class

Supports  
- lambda URLs: 
	- **:any** Any sign
	- **:num** Only numbers
	- **:all** All characters
	- **:an** A-Z a-z 0-9
	- **:url** A-Z a-z 0-9 - _ 
	- **:hex** hexadecimal 
- Regex URLs e.g. /user/(.*)/edit

## Examples

### Static routes
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
	    $response = new \jblond\router\responses();
	    $response->error_503();
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

### Apache rewrite config

```
		RewriteEngine on
		RewriteBase /
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.*)$ index.php [QSA,L]
```
