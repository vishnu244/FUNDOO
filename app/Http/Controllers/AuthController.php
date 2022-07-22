<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //API for Registration
    public function Registerdata(Request $request)
    { 
        $data = $request-> validate([
            'name' => 'string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('CRUDToken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
        ];

        return response($response,201);

    }


     //API for Login
    public function login(Request $request)
    {
        $data = $request-> validate([
            
            'email' => 'required|email|max:100|',
            'password' => 'required|string',
        ]);

        $user = User::where('email',$data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password))
        {
            return response(['message' => 'Invalid Credentials'], 401);
        }
        else
        {
            $token = $user->createToken('CRUDLogin')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 200);
        }
    }


     //API for Logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(['message'=>'Logged Out Successfully']);
    }

}
