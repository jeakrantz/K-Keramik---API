<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if($product != null){
            return $product;
        } else {
            return response()->json([
                'Product not found'
            ], 404);
        }
    }

    public function editProduct(Request $request, $id){

        $product = Product::find($id);

        /* Validerar värdena som skickats i anropet */
        if($product != null) {
            $request->validate([
                "name" => 'required',
                "price" => 'required',
                "stock" => 'required',
                "description" => 'required',
                "category_id" => 'required'
            ]);
    
            if($request->hasFile('image')){
                /* Validerar bild-filen som skickats i anropet */
                $request->validate([
                    'image'=>'required|image|mimes:jpeg,png,jpg,gif,webp|max:2000',
                ]);
    
                $image = $request->file('image');
                $filesize = $request->file('image')->getSize();
    
                $imageName = time() . '.' . $image->getClientOriginalExtension();
    
                $image->move(public_path('uploads'), $imageName);
    
                $imageUrl = asset('uploads/' . $imageName);
    
                unset($request['image']);
    
                $data['image'] = $imageUrl;
    
                $fulldata = array_merge($request->all(), $data);
            } else {
                $fulldata = $request->all();
            }

            /* Lagrar de nya värdena i produkten */
            $product->update($fulldata);

            return response()->json([
                'Product edited'
            ], 200);
        }

        return response()->json([
            'Product not found'
        ], 404 );

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if($product != null){
            $product->update($request->all());
            return $product;
        } else {
            return response()->json([
                'Product not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if($product != null){
            $product->delete();
            return response()->json([
                'Product deleted'
            ]);
        } else {
            return response()->json([
                'Product not found'
            ], 404);
        }
    }

    public function searchProduct($name) {
        return Product::where('name', 'like', '%' . $name . '%')->get();
    }
}
