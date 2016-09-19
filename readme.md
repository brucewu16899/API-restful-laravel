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

####Config/app.php:


	//DINGO API IM HERE
    Dingo\Api\Provider\LaravelServiceProvider::class,


#####composer:


	php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"

	or

	php aritsan vendor:publish


####.env:


	API_STANDARDS_TREE=vnd
	API_SUBTYPE=apirestful
	API_PREFIX=api
	API_DOMAIN=null
	API_VERSION=v1
	API_NAME=Simon api
	API_CONDITIONAL_REQUEST=true
	API_STRICT=false
	API_DEBUG=true


####routes.php:


	$api = app('Dingo\Api\Routing\Router');

	Route::get('/', function () {
	    return view('welcome');
	});

	$api->version('v1',function($api){
		$api->get('hello','App\Http\Controllers\HomeController@index');
	});


##laracasts/generators & zizaco/entrust
 
####app\Providers\AppServiceProvider.php


    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }


####Then in your config/app.php add

in the `providers` array:

	Zizaco\Entrust\EntrustServiceProvider::class,

the `aliases` array:

	'Entrust'   => Zizaco\Entrust\EntrustFacade::class,

####Kernel.php
you also  need to add to `routeMiddleware` :

    'role' => \Zizaco\Entrust\Middleware\EntrustRole::class,
    'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
    'ability' => \Zizaco\Entrust\Middleware\EntrustAbility::class,


####Composer:

	php artisan vendor:publish

and:

	php artisan entrust:migration


* IF ERORR [BEGIN]

	PHP Fatal error:  Class name must be a valid object or a string in G:\simon\apirestful\vendor\zizaco\entrust\src\commands\MigrationCommand.php on line 86


	[Symfony\Component\Debug\Exception\FatalErrorException]
	Class name must be a valid object or a string


You can fix: http://stackoverflow.com/questions/34529621/how-to-fix-in-laravel-5-2-zizaco-entrustmigration-class-name-validation

in vendor/zizaco/entrust/src/commands/MigrationCommand.php on line 86

remove line :

    $usersTable  = Config::get('auth.table');
    $userModel   = Config::get('auth.model');

add line :

	$usersTable  = Config::get('auth.providers.users.table');
	$userModel   = Config::get('auth.providers.users.model');

and config/auth.php file write provider line as like me :

	'providers' => [
	    'users' => [
	        'driver' => 'eloquent',
	        'model' => App\User::class,
	        'table' => 'users',
	    ],

	    // 'users' => [
	    //     'driver' => 'database',
	    //     'table' => 'users',
	    // ],
	],

* END EROR [FINISH]


open `database/migrations/` you can see file migration of `<timestamp>_entrust_setup_tables.php`.
After you use composer to migration:
		
	php artisan migration

 4 new tables will be present:		

	roles — stores role records
	permissions — stores permission records
	role_user — stores many-to-many relations between roles and users
	permission_role — stores many-to-many relations between roles and permissions


###Model

Composer:

	php artisan make:model Role

	php artisan make:model Permission


####app\Role.php using the following example:

	<?php namespace App;

	use Zizaco\Entrust\EntrustRole;

	class Role extends EntrustRole
	{

	}


####app\Permission.php

	<?php namespace App;

	use Zizaco\Entrust\EntrustPermission;

	class Permission extends EntrustPermission
	{

	}

####app\User.php

Add this EntrustUserTrait to User.php (model)
		
	<?php

	use Zizaco\Entrust\Traits\EntrustUserTrait;

	class User extends Eloquent
	{
	    use EntrustUserTrait; // add this trait to your user model

	    ...
	}	



####Model User.php

    use Authenticatable, Authorizable, CanResetPassword, EntrustUseTrait;
    ->
    use Authenticatable, CanResetPassword, EntrustUseTrait;


Intro data to tables: roles, permission

IF use `Seeder` then `Don't` forget

	composer dump-autoload





-------

####Controller.php

Thêm:

	use Dingo\Api\Routing\Helpers;

	abstract class Controller extends BaseController
	{
	    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;
	}

####VerifyCsrfToken

	class VerifyCsrfToken extends BaseVerifier
	{
	    /**
	     * The URIs that should be excluded from CSRF verification.
	     *
	     * @var array
	     */
	    protected $except = [
	        'api/*'
	    ];
	}







##JWTAuth

####Config/app.php

	Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,


	'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
	'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,


####Conposer: 

To create config\jwt.php

	php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"


	>>Result: Copied File [\vendor\tymon\jwt-auth\src\config\config.php] To [\config\jwt.php]


To create key JWT in `config/app.php`

	php artisan jwt:generate

####kernel.php

	'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
	'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,




#####app\Exceptions\Handler.php

    if ($e instanceof ModelNotFoundException) {

        $e = new NotFoundHttpException($e->getMessage(), $e);
        
    }

    if($e instanceof TokenExpiredException){

        return response()->json(['token_expired'], 401);
        
    }else if($e instanceof TokenInvalidException){
        
        return response()->json(['token_invalid'], 401);

    }else if($e instanceof TokenBacklistedException){
        
        return response()->json(['token_backlisted'], 500);

    }

    return parent::render($request, $e);



####routes.php

	$api->version('v1', ['middleware' => 'jwt.auth'], function($api){

		$api->get('users', 'App\Http\Controllers\Auth\AuthController@index');
		$api->get('users/{user_id}', 'App\Http\Controllers\Auth\AuthController@show');

	});


 
####app\Auth\AuthController.php

 Try catch API


    public function authenticate(Request $request){
        $credentials = $request->only('email','password');

        $login = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        try {

            if($login == 'email'){
                $loginCredentials = [
                                'email' => $credentials['email'],
                                'password' => $credentials['password']
                                ];
            }else{
                $loginCredentials = [
                                'phone' => $credentials['email'],
                                'password' => $credentials['password']
                                ];
            }


            if(!$token = JWTAuth::attempt($loginCredentials)){
                return $this->response->errorUnauthorized();
            }

        } catch (JWTException $ex) {
            return $this->response->errorInternal();
        }

        return $this->response->array(compact('token'))->setStatusCode(200);
    }


    public function index(){

        try {

            return User::all();

        } catch (Exception $e) {
            return $e;
        }
        
    }

    public function show($user_id){

        try {
            $user = JWTAuth::parseToken()->toUser();

            if(!$user){
                return $this->response->errorNotFound("User not found");
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $ex) {
            return $this->response->error('Something went wrong');
        }
        return $this->response->array(compact('user'))->setStatusCode(200);
        
    }


