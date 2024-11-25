<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $validate=Validator::make($request->all(),['name'=>'required|string','email'=>'required|email|unique:users,email','password'=>'required', 'address'=> 'required|string']);
        if($validate->fails()){
            return response()->json(['status'=>false,'message'=>'Validation Error',"errors"=>$validate->errors()->all()],401);
        }
        $user=User::create($request->all());
        return response()->json(['status'=>true, 'message'=>'User Created Successfully','user'=> $user],200);
       // 'token'=>$user->createToken('API TOKEN')->plainTextToken
    }
    public function signIn(Request $request)
    {
        // Validate input
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication Failed',
                'errors' => $validate->errors()->all()
            ], 401);
        }
          if (!User::where('email', $request->email)->exists()) {
        return response()->json([
            'status' => false,
            'message' => 'Authentication Failed',
            'errors' => ['The provided email does not exist in our records.']
        ], 401);
    }
        // Attempt to authenticate user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(); // Retrieve authenticated user

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
                'token_type' => 'Bearer'
            ], 200);
        }

        // If authentication fails
        return response()->json([
            'status' => false,
            'message' => 'Credentials do not match our records',
            'errors' =>$request->errors()->all()
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out from all devices'], 200);
    }

    }
