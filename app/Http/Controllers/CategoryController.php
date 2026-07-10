<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /categories
    // public function index(){
    //     $getCategories = Category::select('id','name')->get();
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $getCategories
    //     ], 200);
    // }

        // GET /categories with search
    public function index(Request $request){
        
        $search = $request->search;
        $categories = Category::withCount('products') // products count
        ->when($search, function($query) use ($search){
            $query->where('name' , 'like' , "%$search%");
        })->get();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ], 200);
    }

    // POST /Create categories
    public function store(Request $request){
        $validateData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $category = Category::create($validateData);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ], 201);
    }

     // GET /categories/{id}
    public function show($id){
        $category = Category::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ], 200);
    }


     // PUT /Update categories/{id}
     public function update(Request $request,$id){
         $category = Category::findOrFail($id);
        $validateData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $category->update($validateData);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ], 200);
     }

       // DELETE /categories/{id}
     public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ], 200);
     }
}
