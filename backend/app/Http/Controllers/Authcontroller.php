<?php

namespace App\Http\Controllers;
use App\Models\User;

// use i\Http\Controllers\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Authcontroller extends Controller
{
    public function register(Request $request)
    {
        $fields=$request->validate([
            "name"=> "required|",
            "email"=> "required|email|unique:users",
            "password"=> "required|confirmed",
            ]);
            
            $user= user::create($fields);
            $token= $user->createToken($request->name)->plainTextToken;
            return [
                "token"=> $token,
                "user" =>$user
            ];
    }

    public function login(Request $request)
    {
         $validated = Validator::make($request->all(),
         [ "email"=> "required|exists:users",
           "password"=> "required"
         ]);
         if($validated->fails()){
            return response()->json($validated->errors(),403);
         }      
           $credentials = ['email'=>$request->email,'password'=>$request->password];
           try{
            if (!auth()->attempt($credentials)) {
                return response()->json(['error'=>'Invalid credentials'],403);  
            }
            $user=User::where('email',$request->email)->firstorFail();
            $token=$user->CreateToken($request->email)->plainTextToken;
            return response()->json([
                'token'=> $token,
                'user'=>$user
                ],200);
           }     
           catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],403);

           }
        }


  public function logout(Request $request)
{
    $request->user()->CurrentAccesToken()->delete();
    return response()->json(['success'=> 'User has been logged out succesfully'],200);
}

}