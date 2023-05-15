<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['authenticate', 'register']]);
    }

    public function authenticate(Request $request)
    {
        $data = $request->only('email', 'password');

        $validator = Validator::make($data, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ]);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'string'],
        ]);

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'login' => ["Invalid email or password"]
                ],
            ]);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
    }

    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password');

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ], 400);
        }

        $checkUser = User::where('email', $request->input('email'))->first();

        if (!$checkUser) {
            $user = User::create([
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
                'email' => $request->input('email'),
            ]);

            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => [
                'user' => ['User already exists']
            ]
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);

    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function getUserInfo()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }
}
