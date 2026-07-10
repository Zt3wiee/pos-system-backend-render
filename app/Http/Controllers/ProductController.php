<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products
    // public function index()
    // {
    //     // Logic to retrieve and return all products
    //     $products = Product::with('category')->latest()->get();
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $products
    //     ], 200);
    // }


    // GET /products with search
    public function index(Request $request)
    {
        $search = $request->search;
        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest()
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }

    // POST /Create products
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0'
        ]);
        $products = Product::create($validateData);
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 201);
    }
    // GET /products/{id}
    public function show($id)
    {
        $products = Product::with('category')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }
    // PUT /Update products/{id}
    public function update(Request $request, $id)
    {
        $products = Product::findOrFail($id);
        $validateData = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0'
        ]);
        $products->update($validateData);
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }
    // DELETE /products/{id}
    public function destroy($id)
    {
        $products = Product::findOrFail($id);
        $products->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
