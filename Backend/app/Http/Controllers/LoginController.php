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
            'phone' => 'required|string',
            'store' => 'required|numeric',
            'accepted' => 'required|accepted'
        ]);

        $user = new User;
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');
        $user->password = app('hash')->make($request->input('password'));
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->store = (int)$request->input('store');
        
        try {
            $user->save();
            return response()->json(['data' => $user, 'message' => 'Käyttäjä luotu'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Jotain meni vikaan käyttäjää tehdessä'], 400);
        }
    }

    /**
     * Register a new Admin/Owner user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function createUserAdmin(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:users',
            'address' => 'required|string',
            'phone' => 'required|string',
            'store' => 'required|numeric|min:1',
            'role' => 'required|numeric|min:1|max:2'
        ]);

        $password = str_random(10);
        
        $user = new User;
        $user->fname = $request->input('fname');
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');
        $user->password = app('hash')->make($password);
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->store = (int)$request->input('store');
        $user->role = (int)$request->input('role');

        try {
            $user->save();
            return response()->json(['data' => array_merge($user->toArray(), ['password' => $password]), 'message' => 'Käyttäjä luotu'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Jotain meni vikaan käyttäjää tehdessä'], 400);
        }
    }
    
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
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
     * @return Response
     */
    public function logout()
    {
        Auth::logout(true);

        return response()->json(['message' => 'Kirjauduit ulos'], 200);;
    }
}
