<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;

class LoginController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  Request  $request
     * 
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'address' => 'required|string',
            'phone' => 'required|string'
        ]);

        try {
            $user = new User;
            $user->fname = $request->input('fname');
            $user->lname = $request->input('lname');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->address = $request->input('address');
            $user->phone = $request->input('phone');

            $user->save();

            return response()->json(['user' => $user, 'message' => 'User was created'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User registration failed!'], 400);
        }
    }
    
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * 
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([ 'token' => $token, 'user' => Auth::user() ], 200);
    }

    /**
     * Logout authenticated user
     * 
     * @return Response
     */
    public function logout()
    {
        Auth::logout(true);

        return response()->json(['token' => '', 'message' => 'User logout'], 200);
    }
}
