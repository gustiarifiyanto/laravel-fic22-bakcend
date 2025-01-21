<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Google_client;

class AuthController extends Controller
{
    //function fo register
    public function register (Request $request) 
    {
        //validate the request
        $request->validate([
            "name"=> "required|string",
            "email"=> "required|email|unique:users,email",
            "password"=> "required|string",
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

        function loginGoogle(Request $request)
        {
            //validate request
            $request->validate([
                "id_token"=> "required|string",
            
            ]);

            //get id_token
            $idToken = $request->id_token();
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client -> verifyIdToken($idToken);

            //check if the payload is valid
            if ($payload) {
                $user = User::where('email', $payload['email'])->first();
                $token = $user->createToken('auth_token')->PlainTextToken;
                if ($user) {
                    return response()->json([
                        'status'=> 'success',
                        'message'=> 'User logged is successsfully',
                        'data'=> [
                            'user' => $user,
                            'token'=> $token,
                        ],
                    ],200);
                } else {
                    $user = User::create([
                        'name'=> $payload['name'],
                        'email'=> $payload['email'],
                        'password'=> Hash::make($payload['sub']),
                    ]);
                    $token = $user->createToken('auth_token')->PlainTextToken;
                    return response()->json([
                        'status'=> 'success',
                        'message'=> 'User Created Successfully',
                        'data'=> [
                            'user'=> $user,
                            'token'=> $token,
                        ],
                    ],201);
                }    
            } else {
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Invalid id token',
                ],400);
            }   
        } 
        
        //function for login
        public function login(Request $request)
        {
            //validate the request
            $request->validate([
                'email'=>'required|email',
                'password'=>'required|string'
            ]);

            //check if the user exists
            $user = user::where('email',$request['email'])->first();

            //check if the user exists
            if (!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Invalid credentials',
                ], 401);
            }

            //create token
            $token = $user->createToken('auth_token')->plainTextToken;

            //return response
            return response()->json([
                'status'=> 'success',
                'message'=> 'user logged in successfully',
                'data'=> [
                    'user'=> $user,
                    'token'=> $token,
                ],
            ],200);    
        }

        //function for logout
        public function logout(Request $request)
        {
            //revoke the token
            $request->user()->currentAccessToken()->delete();

            //return response
            return response()->json([
                'status'=> 'success',
                'message'=> 'user logged out successfully',
            ],200);

        }
}
    
