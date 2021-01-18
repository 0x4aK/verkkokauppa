<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Order;
use App\Store;
use App\Product;

class OrderController extends Controller
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
     * Get orders for the store that the user is logged in on
     * Only return orders that have status between 1-3
     * 
     * @return Response
     */
    public function getOrdersFiltered()
    {
        $user = Auth::user();
        $orders = Order::where('store_id', $user->store)->orderBy('status')->get();
        $filtered = $orders->filter(function ($value, $key) {
            return $value['status'] > 0 && $value['status'] < 4;
        });
        $filtered = $filtered->load('user', 'store', 'ordered')->values();

        return response()->json(['data' => $filtered], 200);
    }

    /**
     * Get all orders
     * 
     * @return Response
     */
    public function getOrders()
    {
        $orders = Order::orderBy('status')->orderBy('id')->get();
        $loaded = $orders->load('user', 'store', 'ordered')->values();

        return response()->json(['data' => $loaded], 200);
    }

    /**
     * Get order by id and return with products ordered
     * 
     * @return Response
     */
    public function getOrderById($id) {
        try {
            $order = Order::with(['user', 'ordered', 'store'])->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tilausta ei löydetty!'], 404);
        }

        return response()->json(['data' => $order], 200);
    }

    /**
     * Gets order and edits the status of it
     * 
     * @param  Request  $request
     * 
     * @return Response
     */
    public function editOrderStatus(Request $request, $id) {

        $this->validate($request, [
            'status' => 'required|integer|max:4|min:0',
        ]);

        try {
            $order = Order::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tilausta ei löydetty!'], 404);
        }

        $newStatus = (int)$request->input('status');

        $order->status = !$newStatus ? max($order->status-1, 1) : $newStatus;
        $order->update();

        return response()->json(['data' => $order->load('user', 'store', 'ordered')], 200);
    }

    /**
     * Gets order and edits the status of it
     * Admin version
     * 
     * @param  Request  $request
     * @return Response
     */
    public function editOrderStatusAdmin(Request $request, $id) {

        $this->validate($request, [
            'status' => 'required|integer|max:4|min:0',
        ]);

        try {
            $order = Order::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tilausta ei löydetty!'], 404);
        }

        $newStatus = (int)$request->input('status');

        $order->status = $newStatus;
        $order->update();

        return response()->json(['data' => $order->load('user', 'store', 'ordered')], 200);
    }

    /**
     * Create new order
     * 
     * @param  Request  $request
     * @return Response
     */
    public function createOrder(Request $request)
    {
        $this->validate($request, [
            'products' => 'required|array',
            'store' => 'required|integer',
        ]);

        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'Et ole kirjautunut!'], 403);

        try {
            $store = Store::findOrFail($request->input('store'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ravintolaa ei löydetty!'], 404);
        }

        $order = new Order;

        $order->user()->associate($user);
        $order->store()->associate($store);

        /**
         * set status to accepted when credit card goes thru.
         * we are not doing any actual transactions so we just set it manually
         */
        $order->status = 1;

        $order->save();

        $ordered = collect($request->input('products'));
        $orderedIds = $ordered->pluck('product_id');

        $products = Product::whereIn('id', $orderedIds)->get();
        
        $products->each(function  ($product, $key) use ($order, $ordered) {
            $quantity = $ordered->first(function ($order, $key) use ($product) {
                 return $order['product_id'] === $product->id;
            }, ['quantity' => 1])['quantity'];

            $order->ordered()->attach($product, ['quantity' => $quantity]);
        });

        return response()->json(["message" => "Tilaus onnistui"], 200);
    }

    /**
     * Deletes an order from database.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function deleteOrder(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tilausta ei löydetty!'], 404);
        }

        $order->delete();
        return response()->json(['message' => "Tilaus poistettu!"], 200);
    }
}
