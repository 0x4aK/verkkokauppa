<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function getAuthenticatedUser()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function getAllUsers()
    {
        return response()->json(['data' =>  User::all()], 200);
    }

    /**
     * Get one user.
     *
     * @param  int      $id
     * @return Response
     */
    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Käyttäjää ei löydetty'], 404);
        }

        return response()->json(['data' => $user], 200);
    }

    /**
     * Edits currently authenticated user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function editAuthenticatedUser(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'fname' => 'string',
            'lname' => 'string',
            'address' => 'string',
            'phone' => 'string',
            'store' => 'integer'
        ]);

        if ($request->filled('fname')) $user->fname = $request->input('fname');
        if ($request->filled('lname')) $user->lname = $request->input('lname');
        if ($request->filled('address')) $user->address = $request->input('address');
        if ($request->filled('phone')) $user->phone = $request->input('phone');
        if ($request->filled('store')) $user->store = $request->input('store');

        $user->update();

        return response()->json(['user' => $user, 'message' => 'Tietosi päivitettiin'], 200);
    }

    /**
     * Edits currently authenticated users password.
     *
     * @param  Request  $request
     * @return Response
     */
    public function editAuthenticatedPassword(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);

        if (! password_verify($request->input('old_password'), $user->password)){
            return response()->json(["message" => "Validator error!" , "errors" => ['old_password' => 'Old password not the same']], 400);
        }

        $user->password = app('hash')->make($request->input('password'));

        $user->update();
        Auth::logout(true);

        return response()->json(['message' => 'Salasana vaihdettu!'], 200);
    }

    /**
     * Gets currently authenticated user orders
     *
     * @return Response
     */
    public function getUserOrders() {
        $user = Auth::user();
        $orders = $user->orders->load(['ordered', 'store']);

        return response()->json(['data' => $orders], 200);
    }

    /**
     * Deletes the current authenticated user from database.
     *
     * @param  Request  $request
     * @return Response
     */
    public function deleteAuthenticatedUser(Request $request)
    {
        $user = Auth::user();

        if (! password_verify($request->input('password'), $user->password)){
            return response()->json(["message" => "Validator error!" , "errors" => ['old_password' => 'Wrong password']], 400);
        }

        $user->delete();
        return response()->json(['message' => "Käyttäjätili poistettu!"], 200);
    }

    /**
     * Deletes the current authenticated user from database.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function deleteUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Käyttäjää ei löydetty!'], 404);
        }
        $user->delete();
        return response()->json(['message' => "Käyttäjätili poistettu!"], 200);
    }
}
