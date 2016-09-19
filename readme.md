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

------------------------------------------------------------------------------------------


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



------------------------------------------------------------------------------------------


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




------------------------------------------------------------------------------------------


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
		$api->get('token', 'App\Http\Controllers\Auth\AuthController@getToken');
	});


 
####app\Auth\AuthController.php

 Try catch API


use Illuminate\Http\Request;
use JWTAuth;

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


Note, commit __construct:

    // public function __construct()
    // {
    //     $this->middleware('guest', ['except' => 'getLogout']);
    // }



------------------------------------------------------------------------------------------

##OAUTH

####Add the following to `config/app.php`

	LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class,
	LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider::class,


    'Authorizer' => LucaDegasperi\OAuth2Server\Facades\Authorizer::class,

####app/Http/kernel.php

Add the following  line to the file `app/Http/kernel.php` at the $middleware array

	\LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware::class,

Add the following to $routeMiddleware aray:

	'oauth' => \LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware::class,
	'oauth-user' => \LucaDegasperi\OAuth2Server\Middleware\OAuthUserOwnerMiddleware::class,
	'oauth-client' => \LucaDegasperi\OAuth2Server\Middleware\OAuthClientOwnerMiddleware::class,
	'check-authorization-params' => \LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware::class,


Comment `VerifyCsrfToken`

	    // \App\Http\Middleware\VerifyCsrfToken::class,

Add the following to $routeMiddleware:

	    'csrf' =>  \App\Http\Middleware\VerifyCsrfToken::class,



####composer

	php artisan vendor:publish

Run migrate to create tables require

	php artisan migrate



####App\Providers:

Create `OAuthServiceProvider.php`

	php artisan make:provider OAuthServiceProvider


Add the following at the `OAuthServiceProvider.php`:

	<?php

	namespace App\Providers;

	use App\User;
	use Dingo\Api\Auth\Auth;
	use Dingo\Api\Auth\Provider\OAuth2;
	use Illuminate\Support\ServiceProvider;

	class OAuthServiceProvider extends ServiceProvider{
		
		public function boot(){

			$this->app[Auth::class]->extend('oauth', function ($app){
				
				$provider = new OAuth2($app['oauth2-server.authorizer']->getChecker());

				$provider->setUserResolver(function ($id){
					//logic to return a user by their ID
					$user = User::find($id);
					reutrn $user;
				});

				$provider->setClientResolver(function ($id){
					//logic to return a client by their ID
				});
				return $provider;
			});
		}

		public function register(){
			//
		}

	}

Add this to config/app.php as a dependency

	App\Providers\OAuthServiceProvider::class,

####composer

	composer dump-autoload


####config/oauth2.php

Add the following at the `grant_types` :

	'grant_types' => [
		'password' => [
			'class' => '\League\OAuth2\Server\Grant\PasswordGrant',
			'callback' => '\App\Verifiers\PasswordGrantVerifier@verify',
			'access_token_ttl' => 2592000,
		],
		'refresh_token' => [
			'class' => '\League\OAuth2\Server\Grant\RefreshTokenGrant',
			'access_token_ttl' => 2592000,
			'refresh_token_ttl' => 2592000
		]
	],


continue:

	'access_token_ttl' => 2592000,


####Composer:

	php artisan make:model OAuthClient

Model `OAuthClient.php`:

	class OAuthClient extends Model
	{
	    protected $table = 'oauth_clients';

	    protected $fillable = ['id', 'secret', 'name'];
	    
	}

####Composer

	php artisan make:seed OAuthClientTableSeeder

####database/seeds/OAuthClientTableSeeder.php:

Add the following at the `OAuthClientTableSeeder`:

	class OAuthClientTableSeeder extends Seeder
	{
	    public function run()
	    {
	        //create website client secret

	        \App\OAuthClient::create([
	        	'id' => 'g3b259fdeheh23hh44j3h2j4g',
	        	'secret' => '3d7fh3h4h444g53g233371s7gf2f2v34v',
	        	'name' => 'Android'
	    	]);

	        \App\OAuthClient::create([
	        	'id' => 'g3b259fdfheh22h44j3h2j4g',
	        	'secret' => '3d7fh3h4h474g43g233371s7gf2f2v34v',
	        	'name' => 'website'
	    	]);
	    }
	}

####composer
Run composer dump-autoload to system receive class `OAuthClientTableSeeder`:

	composer dump-autoload

Run seed to into data to table `oauth_clients`

	php artisan db:seed --class=OAuthClientTableSeeder

##app\Verifiers `folder`

Create folder & file:  `app\Verifiers\PasswordGrantVerifier.php`


	<?php

	namespace App\Verifiers;

	use Illuminate\Support\Facades\Auth;

	class PasswordGrantVerifier{

		public function verify($username, $password){

			$credentials = [
				'email' => $username,
				'password' => $password,
			];

			if(Auth::once($credentials)){
				return Auth::user()->id;
			}

			return false;
		}
	}

Run composer:

	composer dump-autoload


####routes.php

Add the following at the `routes.php`:

	use LucaDegasperi\OAuth2Server\Authorizer;

	$api->version('v1', function($api){
		$api->post('oauth/access_token', function(){
			return Authorizer::issueAccessToken();
		});
	});
