<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest', ['except' => 'getLogout']);
    // }

    //after login, create token
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

    public function getToken(){
        $token = JWTAuth::getToken();

        if(!$token){
            return $this->response->errorUnauthorized("Token is invalid");
        }

        try {
            return $refreshedToken = JWTAuth::refresh($token);
        } catch (Exception $e) {
            $this->response->error('Something went wrong');
        }

        return $this->response->array(compact('refreshedToken'));
    }

    // public function destroy(){
    //     $user = JWTAuth::parseToken()->authenticate();

    //     if(!$user){
    //         //fail
    //     }

    //     //processor delete
    //     $user->delete();
    // }

    // public function getAuthenticatedUser(){

    //     try{

    //         if(! $user = JWTAuth::parseToken()->toUser()){
    //             return response()->json(['user not found'],404);
    //         }

    //     }catch(JWTException $ex) {
    //         return $this->response->errorInternal();
    //     }

    //     return $this->response->item($user, new UserTransformer)->setStatusCode(200);
    // }


    //get all user
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


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
