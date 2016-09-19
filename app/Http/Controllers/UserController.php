<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth');
    }
    
    public function validateUser()
    {
        $user = app('Dingo\Api\Auth\Auth')->user();

        if(!$user) {
            $responseArray = [
                'message' => 'Not authorized. Please login again',
                'status' => false
            ];

            return $this->response->array($responseArray)->setStatusCode(403);
        }
        else {
            $responseArray = [
                'message' => 'User is authorized',
                'status' => true
            ];

            return $this->response->array($responseArray)->setStatusCode(200);
        }
    }

}
