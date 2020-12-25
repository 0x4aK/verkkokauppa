<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Order;

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
     * Get all orders
     * 
     * @return Response
     */
    public function getOrders()
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
     * Get order by id and return with products ordered
     * 
     * @return Response
     */
    public function getOrderById($id) {
        try {
            $order = Order::with(['user', 'ordered', 'store'])->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Order not found!'], 404);
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
            return response()->json(['message' => 'Order not found!'], 404);
        }

        $newStatus = (int)$request->input('status');

        $order->status = !$newStatus ? max($order->status-1, 1) : $newStatus;
        $order->update();

        return response()->json(['data' => $order->load('user', 'store', 'ordered')], 200);
    }
}
