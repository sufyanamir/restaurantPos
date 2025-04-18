<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\inv_item_cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryCategoryController extends Controller
{
    public function getCategories()
    {
        try {
            $user = Auth::user();

            $categories = inv_item_cat::where('company_id', $user->company_id)->where('inv_item_cats_status', 1)->get();
            return response()->json(['success' => true, 'message' => "Unit add successfully", 'Categories' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function createCategory(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                "inv_item_cats_name" => "required",
            ]);

            $inventory_category = inv_item_cat::create([
                'company_id' => $user->company_id,
                'user_id' => $user->id,
                'inv_item_cats_name' => $validatedData['inv_item_cats_name'],
            ]);
            return response()->json(['success' => true, 'message' => "Unit add successfully", 'data' => $inventory_category], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function updataCategory(Request $request, $category_id)
    {
        try {

            $validatedData = $request->validate([
                "inv_item_cats_name" => "required",
            ]);

            $category = inv_item_cat::find($category_id);
            if (!$category) {
                return response()->json(['success'  => true, 'message' => 'Category not found'], 500);
            }


            $category->inv_item_cats_name = $validatedData['inv_item_cats_name'];
            $category->update();
            return response()->json(['success' => true, 'message' => "Unit updated successfully", 'data' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function deleteCategory($category_id)
    {
        try {

            $category = inv_item_cat::find($category_id);
            if (!$category) {
                return response()->json(['success'  => true, 'message' => 'Category not found'], 500);
            }

            $category->inv_item_cats_status = 0;
            $category->update();
            return response()->json(['success' => true, 'message' => "Category delete successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
