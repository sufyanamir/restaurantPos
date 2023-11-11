<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Services;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $user_details = session('user_details');
        $userId = $user_details['user_id'];
        $companyId = $user_details['company_id'];

        
        $customersCount=Customers::where('added_user_id', $userId)->count();
        $servicesCount=Services::where('added_user_id', $userId)->count();
        $adminCount = User::where('user_role',1)->count();
        $adminCustomerCount = Customers::count();
        $staffCount = User::where('user_role',2)->where('company_id', $companyId)->count();
        $user_details = session('user_details');
        return view('dashboard', [
            'userDetails' => $user_details,
            'adminCount'=>$adminCount,
            'adminCustomerCount'=>$adminCustomerCount,
            'customersCount'=>$customersCount,
            'staffCount'=>$staffCount,
            'servicesCount'=>$servicesCount,
        ]);
    }
}
