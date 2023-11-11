<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    //add company social links
    Route::get('/getCompanyExpenses', [ApiController::class, 'getCompanyExpenses']);
    Route::post('/addExpense', [ApiController::class, 'addExpense']);
    Route::post('/addCompanyLinks', [ApiController::class, 'addCompanyLinks']);
    //add company social links
    //feed
    Route::get('/getFeed', [ApiController::class, 'getFeed']);
    //feed

    //image gallery
    Route::post('/postMedia', [ApiController::class, 'postMedia']);
    Route::get('/getMedia', [ApiController::class, 'getMedia']);
    //image gallery

    //Order
    Route::post('/createOrder', [ApiController::class, 'createOrder']);
    Route::get('/getOrders', [ApiController::class, 'getOrders']);
    Route::get('/getOrder', [ApiController::class, 'getOrderDetails']);
    Route::post('/updateOrderStatus', [ApiController::class, 'updateOrderStatus']);
    //Order

    // Your authenticated routes here
    Route::get('/getUser', [ApiController::class, 'getUserDetails']);
    // Add other authenticated routes here

    //service APIs
    Route::post('/addService', [ApiController::class, 'addService']);
    Route::post('/service/update/{id}', [ApiController::class, 'updateService']);
    Route::match(['post', 'get'], '/service/delete/{id}', [ApiController::class, 'deleteService']);
    Route::get('/getServices', [ApiController::class, 'getService']);
    Route::get('/getService', [ApiController::class, 'getServiceDetail']);
    Route::post('/assignCustomer', [ApiController::class, 'assignCustomer']);
    //service APIs

    //customer APIs
    Route::post('/addCustomer', [ApiController::class, 'addCustomer']);
    Route::post('/customer/update/{id}', [ApiController::class, 'updateCustomer']);
    Route::match(['post', 'get'], '/customer/delete/{id}', [ApiController::class, 'deleteCustomer']);
    Route::get('/getCustomers', [ApiController::class, 'getCustomer']);
    Route::get('/getCustomer', [ApiController::class, 'getCustomerDetail']);
    //customer APIs

    //staff APIs
    Route::post('/addStaff', [ApiController::class, 'addStaff']);
    Route::post('/staff/update/{id}', [ApiController::class, 'updateStaff']);
    Route::match(['post', 'get'], '/staff/delete/{id}', [ApiController::class, 'deleteStaff']);
    Route::get('/getStaffs', [ApiController::class, 'getStaff']);
    Route::get('/getStaff', [ApiController::class, 'getStaffDetail']);
    //staff APIs

    Route::get('/adminDashboard', [ApiController::class, 'adminDashboard']);

    Route::post('/user/update', [ApiController::class, 'updateUserDetail']);
});

Route::middleware('auth:sanctum')->post('/logout', [ApiController::class, 'logout']);

//authentication
Route::match(['post'], '/login', [ApiController::class, 'login']);
Route::match(['post'], '/register', [ApiController::class, 'makeRequest']);
Route::post('/forgotPassword', [ApiController::class, 'forgotPassword']);
Route::post('/validateOtp', [ApiController::class, 'validateOtp']);
Route::post('/resetPassword', [ApiController::class, 'resetPassword']);
//authenticationl