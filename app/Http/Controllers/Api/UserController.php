<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Hash;

class UserController extends Controller
{
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|max:100|unique:users',
            'password' => 'required_with:confirm_password|min:6|max:100',
            'confirm_password' => 'required_with:password|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Fill in the required fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 200);
    }


    public function login(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Fill in the required fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = user::where ('email', $request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)){
                $token = $user -> createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Login Successful',
                    'token' => $token,
                ], 400);
            } else {
                return response()->json([
                    'message' => 'Email or Password incorrect',
                ], 400);
            }

        } else {
            return response()->json([
                'message' => 'Email or Password incorrect',
            ], 400);
        }
    }


    public function getuser (Request $request)
    {
        return response()->json([
            'message' => 'Here are your details',
            'user' => $request -> user()
        ], 400);
    }


    public function getusers (Request $request)
    {
        $user = user::all();

        return response()->json([
            'message' => 'This is a list of all registered users',
            'user' => true,
            'user' => $user
        ]);
    }


    public function logout (Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User successfully logged out',
        ], 400);
    }


    public function update (Request $request, $id)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|max:100|unique:users',
            'password' => 'required_with:confirm_password|min:6|max:100',
            'confirm_password' => 'required_with:password|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Fill in the required fields',
                'errors' => $validator->errors()
            ], 422);
        } 

        $user = user::where ('id', $id)->first();
        if($user){
            if($user->id==$request->user()->id){
                
            //    $user->update($request->all());
                if (!$request->name == ''){
                    $user->name = $request->name;
                } else if (!$request->email == ''){
                    $user->email = $request->email;
                } else if (!$request->password == ''){
                    $user->password = hash::make($request->password);
                }
                
                $user->save();
        
                return response()->json([
                    'message' => 'User details updated',
                    'user' => $user
                ], 200);

            } else {
                return response()->json([
                    'message' => 'Access denied',
                ], 403);
            }
        }
    }


    public function delete (Request $request, $id)
    {
        $user = user::where ('id', $id)->first();
        if ($user){
            if($user->id==$request->user()->id){
                $user->delete();

        return response()->json([
            'message' => 'User Deleted successfully'
        ], 200);

            } else {
                return response()->json([
                    'message' => 'Access denied',
                ], 403);
            }
        }
        
    }
}
