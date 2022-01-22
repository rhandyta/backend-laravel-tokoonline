<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $products;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
            'name_product' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 401);
        }
        $newProduct = Product::create([
            'category_id' => $request->category_id,
            'name_product' => $request->name_product,
            'slug' => \Str::slug($request->name_product),
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $request->image,
        ]);
        if ($newProduct) {
            $url = asset('') . 'storage';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('public', $newProduct->id . '.' . $extension);
            $newProduct->image = substr($path, 7, 13);
            $newProduct->imagepath = $url . '/' . substr($path, 7, 13);
            $newProduct->save();
            if ($newProduct) {
                return response()->json(['success' => true, 'message' => 'Product has been added!', 'data' => $newProduct], 201);
            }
            return response()->json(['success' => false, 'message' => 'Image product failure added!'], 400);
        }
        return response()->json(['success' => false, 'message' => 'Image product failure added!'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (!$product) {
            return response()->json([
                'success' => false,
                'data' => 'product not found',
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name_product' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'description' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()], 409);
        }
        $id = $product->id;
        $updateData = Product::find($id);
        $updateData->category_id = $request->category_id;
        $updateData->name_product = $request->name_product;
        $updateData->slug = \Str::slug($request->name_product);
        $updateData->price = $request->price;
        $updateData->stock = $request->stock;
        $updateData->description = $request->description;
        if ($request->image) {
            $updateData = $request->image;
        }
        $updateData->update();
        if ($updateData) {
            if ($request->image) {
                $url = asset('') . 'storage';
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('/product/images', $request->id . '.' . $extension);
                $updateData->image = $path;
                $updateData->imagepath = $url . $path;
                $updateData->update();
                return response()->json(['success' => true, 'message' => 'Product has been updated!', 'data' => $updateData], 201);
            }
        }
        return response()->json(['success' => true, 'message' => 'Product has been updated!', 'data' => $updateData], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $deleteProduct = Product::findOrFail($product->id);
        $resultProduct = $deleteProduct->delete();
        if ($resultProduct) {
            return response()->json(['success' => true, 'message' => 'Product has been deleted!'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Product failure deleted!'], 400);
    }
}
