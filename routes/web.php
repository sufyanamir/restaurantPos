<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ServiceRequestsController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\LanguageManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['customauth','LanguageManager'])->group(function () {

Route::get('/settings',[SettingsController::class,'index']);
Route::post('/settings/update/{id}',[SettingsController::class,'update']);


Route::get('/requests', [ServiceRequestsController::class, 'index']);
Route::post('/requests/approve/{id}', [ServiceRequestsController::class, 'update']);
Route::post('/requests/delete/{id}', [ServiceRequestsController::class, 'destroy'])->name('request.delete');

// company
Route::match(['post'], 'company/store', [CompanyController::class, 'addCompany']);
Route::get('/company', [CompanyController::class, 'index']);
Route::get('/company/delete/{id}', [CompanyController::class,'destroy'])->name('company.delete');
Route::post('/company/update/{id}', [CompanyController::class, 'update']);
// company

// staff
Route::match(['post'], 'staff/store', [StaffController::class, 'addStaff']);
Route::get('/staff', [StaffController::class,'index']);
Route::get('/staff/delete/{id}', [StaffController::class,'destroy'])->name('staff.delete');
Route::post('/staff/update/{id}', [StaffController::class, 'update']);
// staff

// Customer
Route::match(['post'], 'customers/store', [CustomerController::class, 'addCustomer']);
Route::get('/customers', [CustomerController::class,'index']);
Route::get('/customers/delete/{id}', [CustomerController::class,'destroy'])->name('customer.delete');
Route::post('/customer/update/{id}', [CustomerController::class, 'update']);
// Customer

// Services
Route::post('services/store', [ServicesController::class, 'addService']);
Route::get('/services', [ServicesController::class, 'index']);
Route::get('/services/delete/{id}', [ServicesController::class,'destroy'])->name('service.delete');
Route::post('/services/update/{id}', [ServicesController::class, 'update']);
// Services
//Gallery
Route::get('/gallery',[GalleryController::class,'index']);
//Gallery

Route::get('/dashboard', [DashboardController::class, 'index']);

// Route::match(['post','get'],'/lang_change', [LanguageController::class, 'lang_change']);
Route::post('/lang_change', [LanguageController::class, 'lang_change']);
});


Route::get('/register',function(){
    return view('register');
});

// Route::get('/product', function () {
//     return view('products');
// });


//Auth
Route::get('/', [LoginController::class,'index']);
Route::post('/', [LoginController::class,'login']);
Route::match(['get', 'post'], '/logout', [LoginController::class,'logout']);
//Auth

//Requesting Service
Route::post('/register', [ServiceRequestsController::class, 'makeRequest']);
//Requesting Service
