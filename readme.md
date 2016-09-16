#API RESTFUL LARAVEL 5.1

	By Simon Nguyen
	hiennv219.github.io
	skype: nvhien129@hotmail.com


#Use package
	
	Laravel 5.1
		
	dingo/api
	https://github.com/dingo/api

	laracasts/generators
	https://github.com/laracasts/Laravel-5-Generators-Extended

	zizaco/entrust
	https://github.com/Zizaco/entrust

	tymon/jwt-auth
	https://github.com/tymondesigns/jwt-auth

	lucadegasperi/oauth2-server-laravel
	https://github.com/lucadegasperi/oauth2-server-laravel


#Composer.json

Supplement(add) into composer.json

    "require": {
        "dingo/api": "1.0.x@dev",
        "zizaco/entrust": "5.2.x-dev",
        "tymon/jwt-auth": "0.5.*",
        "lucadegasperi/oauth2-server-laravel": "^5.2"
    },
    "require-dev": {
        "laracasts/generators": "^1.1"
    },


#action

##dingo/api

###Step 1:

- app\providers\AppServiceProvider.php:


	//DINGO API IM HERE
    Dingo\Api\Provider\LaravelServiceProvider::class,


- composer:


	php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"

	or

	php aritsan vendor:publish


- .env:


	API_STANDARDS_TREE=vnd
	API_SUBTYPE=apirestful
	API_PREFIX=api
	API_DOMAIN=null
	API_VERSION=v1
	API_NAME=Simon api
	API_CONDITIONAL_REQUEST=true
	API_STRICT=false
	API_DEBUG=true


- routes.php:


	$api = app('Dingo\Api\Routing\Router');

	Route::get('/', function () {
	    return view('welcome');
	});

	$api->version('v1',function($api){
		$api->get('hello','App\Http\Controllers\HomeController@index');
	});


