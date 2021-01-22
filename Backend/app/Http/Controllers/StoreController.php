<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    /**
     * Edit a store
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function editStore(Request $request, $id) 
    {
        $this->validate($request, [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'open' => 'required|array',
        ]);

        if (!$id) $store = new Store;
        else {
            try {
                $store = Store::findOrFail($id);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Ravintolaa ei löydetty!'], 404);
            }
        }

        $store->name = $request->input('name');
        $store->address = $request->input('address');
        $store->phone = $request->input('phone');
        $store->open = $request->input('open');

        if ($request->filled('img')) $store->img = $request->input('img');

        if (!$id) $store->save();
        else $store->update();

        $message = !$id ? 'Ravintola lisättiin onnistuneesti' : 'Ravintola päivitettiin';

        return response()->json(['message' => $message, 'data' => $store], 200);
    }

    /**
     * Deletes a store from database.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function deleteStore(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ravintolaa ei löydetty!'], 404);
        }

        $store->delete();
        return response()->json(['message' => "Ravintola poistetu!"], 200);
    }
}
