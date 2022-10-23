<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
   
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required'
        ]);

        if($validator->fails()){

            return response()->json([
                'validation_err' => $validator->messages(),
            ]);

        }else{

            User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => "User added successfully",
            ]);
        }
    }

}
