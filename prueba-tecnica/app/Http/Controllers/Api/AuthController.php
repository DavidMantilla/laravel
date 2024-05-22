<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{


    public function register(Request $request)
    {  
        
        $request->validate([
            'name' => ["required"],
            'email' => ["required","email","unique:users"],
            'password' => ['required',' confirmed'],
        ]);
           
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return  response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {   
        $request->validate([
            "email" => ["required", "email"], "password" => ['required']
        ]);
        
        $user = User::where("email", "=", $request->email)->first();
        if (isset($user->id)) {
            if (hash::check($request->password, $user->password)) {

                $token = $user->createToken("auth_token")->plainTextToken;
                $cookie = cookie('cookie_token', $token, 60 * 24);
                return response(["token" => $token],Response::HTTP_OK)->withCookie($cookie);
            }else{
                return  response(["message"=> "clave invalida"], Response::HTTP_UNAUTHORIZED);
            }
        }
        else{
            return  response(["message"=> "usuario invalido"], Response::HTTP_UNAUTHORIZED);
        }
      
    }

    public function userProfile(Request $request)
    {

        return  response()->json([
            "mensaje" => "perfil de usuario", 
            "userdata" => auth()->user()
        ],Response::HTTP_OK);
    }


    public function logOut()
    {   auth()->user()->tokens()->delete();
        $cookie = Cookie::forget('cookie_token');
        return response(["mensaje" =>"cerraste session" ],Response::HTTP_OK)->withCookie($cookie);
       
    }
    public function allUser()
    {
        return  response()->json(["mensaje" => "todos los usuarios"]);
    }
    
}
