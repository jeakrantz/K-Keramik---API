<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }

        /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if($category != null){
            return $category;
        } else {
            return response()->json([
                'Category not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if($category != null){
            $category->update($request->all());
            return $category;
        } else {
            return response()->json([
                'Category not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if($category != null){
            $category->delete();
            return response()->json([
                'Category deleted'
            ]);
        } else {
            return response()->json([
                'Category not found'
            ], 404);
        }
    }

    public function addCategory(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        return Category::create($request->all());
    }

    /* Lägga till produkt som kopplas till en specifik kategori */
    public function addProduct(Request $request, $id){
        
        /* Hämtar rätt kategori på id */
        $category = Category::find($id);

        /* Om kategorin inte hittas skickas ett 404 */
        if($category === null){
            return response()->json([
                'Category not found.'
            ], 404);
        }

        /* Validerar värdena skickade i anropet */
        $request->validate([
            "name" => 'required',
            "price" => 'required',
            "stock" => 'required',
            "image" => 'required',
            "description" => 'required'
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

        }

        /* Lagrar produkten i databasen */
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->image = $imageUrl;
        $product->description = $request->description;
        $category->products()->save($product);
        
        return response()->json([
            'Product added to category'
        ], 200);
    }

    /* Hämtar produkter i en specifik kategori */
    public function getProductsByCategory($id) {
        $category = Category::find($id);
        
        if($category === null){
            return response()->json([
                'Category not found.'
            ], 404);
        }

        $products = Category::find($id)->products;

        return $products;
    }
}
