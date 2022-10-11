<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if($validate->fails())
        {
            return response()->json([
                'status' => false,
                'msg' => "error",
                'errors' => $validate->errors(),
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'msg' => "User register successfully",
            'token' => $user->createToken("Imgupload")->plainTextToken,
        ], 200);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validate->fails())
        {
            return response()->json([
                'status' => false,
                'msg' => "error",
                'errors' => $validate->errors(),
            ], 401);
        }

        if(!Auth::attempt($request->only(['email', 'password'])))
        {
            return response()->json([
                'status' => true,
                'msg' => "Email or Password does not match."
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'msg' => "User login Successfully",
            'token' => $user->createToken("Imgupload")->plainTextToken,
        ], 200);
    }
}
