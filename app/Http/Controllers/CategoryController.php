<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::where('status', 1)->get();
        return view('category/index', [
            'categories' => $categories
        ]);
    }

    public function get($id = null)
    {
        if ($id !== null) {
            $category = Category::find($id);
        } else {
            $category = Category::where('status', 1)->get();
        }

        return response()->json([
            'data' => $category
        ]);
    }

    public function upsert(Request $request){
        $request->validate([
            'name' => 'required|unique:category,name,' . $request->id,
            'description' => 'required',
            'icon' => 'required',
            'type' => 'required',
            'color' => 'required',
        ]);

        if($request->id){
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->icon = $request->icon;
            $category->type = $request->type;
            $category->color = $request->color;
            $category->updated_by = 1;
            $category->save();
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category
            ]);
        } else {
            $category = Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'icon' => $request->icon,
                'type' => $request->type,
                'color' => $request->color,
                'created_by' => 1,
                'status' => 1,
            ]);
            return response()->json([
                'message' => 'Category created successfully',
                'category' => $category
            ]);
        }
    }

    public function delete($id){
        $category = Category::find($id);
        if($category){
            $category->status = 0;
            $category->save();
            return response()->json([
                'message' => 'Category deleted successfully',
                'category' => $category
            ]);
        } else {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
    }
}
