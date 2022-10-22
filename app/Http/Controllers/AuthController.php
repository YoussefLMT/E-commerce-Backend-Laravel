<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function register(Request $req){
        
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if($validator->fails()){

            return response()->json([
                'validation_err' => $validator->messages(),
            ]);

        }else{

            $user = User::create([
                'name' => $req->name,
                'email' => $req->email,
                'password' => Hash::make($req->password),
            ]);


            return response()->json([
                'status' => 200,
                'message' => 'Your account created successfully',
            ]);
            
        }
    }


    public function login(Request $req){

        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){

            return response()->json([
                'validation_err' => $validator->messages(),
            ]);

        }else{

            $user = User::where('email', $req->email)->first();
 
            if(!$user || !Hash::check($req->password, $user->password)) {

                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Login!, Please Try Again',
                ]);

            }else{

                if($user->role == 'admin'){

                    $token = $user->createToken($user->email.'_token', ['server:admin'])->plainTextToken;

                }else if($user->role == 'user'){
                    
                    $token = $user->createToken($user->email.'_token', [''])->plainTextToken;

                }

                return response()->json([
                    'status' => 200,
                    'token' => $token,
                    'role' => $user->role,
                    'message' => 'You are logged in successfully',
                ]);
            }
        }
    }


    public function logOut(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logged out successfully',
        ]);
    }
}
