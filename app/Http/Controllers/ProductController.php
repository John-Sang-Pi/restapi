<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = auth()->user()->products()->get();

        if($products) {
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        }
        else
            return response()->json([
                'success' => false,
                'message' => 'No product is added!'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $product->toArray()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = auth()->user()->products()->find($id);

        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product '.$id.' not found!'
            ], 400);
        }
       
       return response()->json([
            'success' => true,
            'data' => $product->toArray()
       ], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = auth()->user()->products()->find($id);

        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product '.$id.' not found!'
            ]);
        }

        $update = $product->fill($request->all());
        $updated = $update->save();

        if($updated) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be updated'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product ' . $id . ' not found.'
            ], 400);
        }
 
        if ($product->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted.'
            ], 500);
        }
    }
}
