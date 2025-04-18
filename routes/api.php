<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\InventoryCategoryController;
use App\Http\Controllers\api\InventoryUnitController;
use App\Http\Controllers\OrderController;


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
    // Attendance apis
    Route::post('/addAttendance', [ApiController::class, 'addAttendance']);
    Route::get('/getAttendance', [ApiController::class, 'getAttendance']);
    // Attendance apis

    // Supplier apis
    Route::post('/addSupplier', [ApiController::class, 'addCustomer']);
    Route::get('/getSuppliers', [ApiController::class, 'getSuppliers']);
    // Supplier apis

    // Inventory apis
    Route::post('/addInventory', [ApiController::class, 'addInventory']);
    Route::get('/getInventory', [ApiController::class, 'getInventory']);
    Route::post('/addInventoryPlus', [ApiController::class, 'addInventoryPlus']);
    Route::post('/addInventoryMinus', [ApiController::class, 'addInventoryMinus']);
    Route::get('/getInventoryPlus', [ApiController::class, 'getInventoryPlus']);
    Route::get('/getInventoryMinus', [ApiController::class, 'getInventoryMinus']);
    // Inventory apis

    // table apis
    Route::post('/addTable', [ApiController::class, 'addTable']);
    Route::post('/updateTable', [ApiController::class, 'updateTable']);
    Route::get('/deleteTable/{id}', [ApiController::class, 'deleteTable']);
    Route::get('/getTables', [ApiController::class, 'getTables']);
    // table apis

    // kitchen apis
    Route::post('/addKitchen', [ApiController::class, 'addKitchen']);
    Route::post('/updateKitchen', [ApiController::class, 'updateKitchen']);
    Route::get('/deleteKitchen/{id}', [ApiController::class, 'deleteKitchen']);
    Route::get('/getKitchen', [ApiController::class, 'getKitchen']);
    // kitchen apis

    //company apis
    Route::get('/getCompanyDetails', [ApiController::class, 'getCompanyDetails']);
    Route::post('/updateCompanyDetails', [ApiController::class, 'updateCompanyDetails']);
    //company apis

    // branch apis
    Route::post('/addBranch', [ApiController::class, 'addBranch']);
    Route::match(['post', 'get'], '/branch/delete/{id}', [ApiController::class, 'deleteBranch']);
    Route::get('/getBranches', [ApiController::class, 'getBranches']);
    Route::post('/branch/update/{id}', [ApiController::class, 'updateBranch']);
    // branch apis
    // category apis
    Route::post('/addProductCategory', [ApiController::class, 'addProductCategory']);
    Route::post('/productCategory/update', [ApiController::class, 'updateProductCategory']);
    Route::get('/getProductCategory', [ApiController::class, 'getProductCategory']);
    Route::get('/deleteCategory/{id}', [ApiController::class, 'deleteCategory']);
    // category apis

    //product apis
    Route::post('/addProduct', [ApiController::class, 'addProduct']);
    Route::post('/product/update/{id}', [ApiController::class, 'updateProduct']);
    Route::match(['post', 'get'], '/product/delete/{id}', [ApiController::class, 'deleteProduct']);
    Route::get('/getProducts', [ApiController::class, 'getProducts']);
    //product apis
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
    Route::post('/createOrderFromApp', [ApiController::class, 'createOrderFromApp']);
    ROute::post('/updateOrderFromApp', [ApiController::class, 'updateOrderFromApp']);
    // Route::get('/getOrders', [ApiController::class, 'getOrders']);
    Route::match(['get', 'post'], '/getOrders', [ApiController::class, 'getOrders']);
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
    Route::post('/customer/update', [ApiController::class, 'updateCustomer']);
    Route::match(['post', 'get'], '/customer/delete/{id}', [ApiController::class, 'deleteCustomer']);
    Route::get('/getCustomers', [ApiController::class, 'getCustomers']);
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

    Route::get('/ledger/{id}', [ApiController::class, 'ledger']);

    Route::get('/getVouchers', [ApiController::class, 'getVouchers']);
    Route::post('/deleteVoucher', [ApiController::class, 'deleteVoucher']);
    Route::post('/updateVoucher', [ApiController::class, 'updateVoucher']);
    Route::post('/addVoucher', [ApiController::class, 'addVoucher']);

    Route::post('/appDashboard', [ApiController::class, 'appDashboard']);

    Route::controller(InventoryUnitController::class)->group(function () {
        Route::post('/addInventoryUnit', 'CreateUnit');
        Route::get('/getUnits', 'getUnits');
        Route::put('/updataUnit/{unit_id}', 'updataUnit');
        Route::delete('/deleteUnit/{unit_id}', 'deleteUnit');
    });
    Route::controller(InventoryCategoryController::class)->group(function () {
        Route::get('/getInventoryCategories', 'getCategories');
        Route::post('/addInventoryCategory', 'createCategory');
        Route::put('/updataInventoryCategory/{category_id}', 'updataCategory');
        Route::delete('/deleteInventoryCategory/{category_id}', 'deleteCategory');
    });
});

Route::middleware('auth:sanctum')->post('/logout', [ApiController::class, 'logout']);

//authentication
Route::match(['post'], '/login', [ApiController::class, 'login']);
Route::match(['post'], '/register', [ApiController::class, 'makeRequest']);
Route::post('/forgotPassword', [ApiController::class, 'forgotPassword']);
Route::post('/validateOtp', [ApiController::class, 'validateOtp']);
Route::post('/resetPassword', [ApiController::class, 'resetPassword']);
//authenticationl

//delete

// Route::post('/createOrder', [OrderController::class, 'createOrder']);
