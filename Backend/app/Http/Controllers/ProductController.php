<?php

namespace App\Http\Controllers;

use App\Product;

class ProductController extends Controller
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
     * Get featured products
     * 
     * @return Response
     */
    public function getFeaturedProducts() 
    {
        return response()->json([
            'data' => Product::featured()->get()
        ], 200);
    }

    /**
     * Get all products
     * 
     * @return Response
     */
    public function getProducts() 
    {
        return response()->json([
            'data' => Product::all()
        ], 200);
    }


    /**
     * Get product by the product id
     *
     * @return Response
     */
    public function getProductById($id) 
    {
        try {
            $product = Product::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found!'], 404);
        }

        return response()->json(['data' => $product], 200);
    }
}
