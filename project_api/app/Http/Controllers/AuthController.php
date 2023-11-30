<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Registrera användare
    public function register(Request $request){
        $validatedUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]
        );

        //Fel vid validering
        if($validatedUser->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validatedUser->errors()
            ], 401);
        }

        //Validering OK, returnera token
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $token = $user->createToken('APITOKEN')->plainTextToken;

        $response = [
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    //Logga in
    public function login(Request $request) {
        $validatedUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        //Fel vid validering
        if($validatedUser->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validatedUser->errors()
            ], 401);
        }

        //Felaktig inloggning
        if(!auth()->attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Invalid email/password'
            ], 401);
        }

        //Lyckad inloggning, skicka token
        $user = User::where('email', $request->email)->first();

        return response()->json([
            'message' => 'User logged in',
            'token' => $user->createToken('APITOKEN')->plainTextToken
        ], 200);
    }

    //Logga ut
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        $response = [
            'message' => 'User logged out'
        ];
        
        return response($response, 200);
    }
}
