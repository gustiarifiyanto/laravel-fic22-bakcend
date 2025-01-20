<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //function fo register
    public function register (Request $request) 
    {
        //validate the request
        $request->validate([
            "name"=> "required|string",
            "email"=> "required|email|unique:users,email ",
            "password"=> "required|string|confirmed"
        ]);

        //create user
        $user = User::create([
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=> Hash::make($request->password),
        ]);

        //return response
        return response()->json([
            "status"=> "success",
            "message"=> "user created succesfully",
            "data"=> $user,

        ], 201);
    }
    
}
    
