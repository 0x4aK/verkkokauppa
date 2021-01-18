<?php

/**  
 *  TODO: Create method that takes in array of product ids
 *  and return product info in array.
 *  Maybe use getProducts method (check if id_array in body)
 * 
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'data' => Product::orderBy('category')->get()
        ], 200);
    }

    /**
     * Get all products
     * 
     * @param  Request  $request
     * @return Response
     */
    public function getProductsList(Request $request) 
    {
        $products = Product::whereIn('id', $request->input())->get();

        return response()->json(['data' => $products], 200);
    }

    /**
     * Get product by the product id
     *
     * @param  int      $id
     * @return Response
     */
    public function getProductById($id) 
    {
        try {
            $product = Product::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found!'], 404);
        }

        $related = Product::inRandomOrder()->limit(5)->get();

        return response()->json(['data' => ['product' => $product, 'related' => $related]], 200);
    }

    /**
     * Edit a product
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function editProduct(Request $request, $id) 
    {
        $this->validate($request, [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'is_featured' => 'required|boolean',
            'description' => 'required|string',
            'category' => 'required|integer|min:1'
        ]);

        if (!$id) $product = new Product;
        else {
            try {
                $product = Product::findOrFail($id);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Tuotetta ei löydetty!'], 404);
            }
        }

        $product->name = $request->input('name');
        $product->price = (float)$request->input('price');
        $product->is_featured = (bool)$request->input('is_featured');
        $product->description = $request->input('description');
        $product->category = (int)$request->input('category');

        if (!$id) $product->save();
        else $product->update();

        $message = !$id ? 'Tuote lisättiin onnistuneesti' : 'Tuote päivitettiin';

        return response()->json(['message' => $message, 'data' => $product], 200);
    }

    /**
     * Deletes a product from database.
     *
     * @param  Request  $request
     * @param  int      $id
     * @return Response
     */
    public function deleteProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tuotetta ei löydetty!'], 404);
        }

        $product->delete();
        return response()->json(['message' => "Tuote poistettu!"], 200);
    }
}
