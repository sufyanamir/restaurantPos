<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Api order pass
    public function createOrder(Request $request){


        $orderID = $request->input('createdAt');
        $uploaded_sts = 1;
        $data = [
            'sts' => true,
            'msg' => 'Order uploaded',
            'createdAt' => $orderID,
            'isUploaded' => 1,
        ];
        // Return a JSON response using the response() function
    return response()->json($data);
    }
}
