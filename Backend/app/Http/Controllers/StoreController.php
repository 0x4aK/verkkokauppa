<?php

namespace App\Http\Controllers;

use App\Store;

class StoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all stores
     * 
     * @return Response
     */
    public function getStores()
    {
        return response()->json(['data' => Store::all()], 200);
    }
}
