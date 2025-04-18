<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\inv_unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryUnitController extends Controller
{

    public function getUnits()
    {
        try {
            $user = Auth::user();

            $units = inv_unit::where('company_id', $user->company_id)->where('inv_unit_status', 1)->get();
            return response()->json(['success' => true, 'message' => "Unit add successfully", 'Units' => $units], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function CreateUnit(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                "inv_unit_name" => "required",
                "inv_unit_symbol" => "required"
            ]);

            $inventory_unit = inv_unit::create([
                'company_id' => $user->company_id,
                'user_id' => $user->id,
                'inv_unit_name' => $validatedData['inv_unit_name'],
                'inv_unit_symbol' => $validatedData['inv_unit_symbol'],
            ]);
            return response()->json(['success' => true, 'message' => "Unit add successfully", 'data' => $inventory_unit], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }


    public function updataUnit(Request $request, $unit_id)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                "inv_unit_name" => "required",
                "inv_unit_symbol" => "required"
            ]);

            $unit = inv_unit::find($unit_id);
            if (!$unit) {
                return response()->json(['success'  => true, 'message' => 'Unit not found'], 500);
            }


            $unit->inv_unit_name = $validatedData['inv_unit_name'];
            $unit->inv_unit_symbol =  $validatedData['inv_unit_symbol'];
            $unit->update();
            return response()->json(['success' => true, 'message' => "Unit updated successfully", 'data' => $unit], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function deleteUnit($unit_id)
    {
        try {
            $user = Auth::user();
            $unit = inv_unit::find($unit_id);
            if (!$unit) {
                return response()->json(['success'  => true, 'message' => 'Unit not found'], 500);
            }

            $unit->inv_unit_status = 0;
            $unit->update();
            return response()->json(['success' => true, 'message' => "Unit deleted"], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
