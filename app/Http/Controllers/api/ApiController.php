<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\forgotPasswordMail;
use App\Mail\StaffRegistrationMail;
use App\Models\Customers;
use App\Models\ServiceOverheads;
use App\Models\ServiceRequests;
use App\Models\Services;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\AdditionalItems;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\CompanyExpense;
use App\Models\imageGallery;
use App\Models\Kitchen;
use App\Models\OrderAdditionalItems;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\RestaurantTables;
use App\Models\Trasanctions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Unique;

class ApiController extends Controller
{
    protected $appUrl = 'https://adminpos.thewebconcept.com/';

    //----------------------------------------------------kitchen screen APIs------------------------------------------------------//
    // update customer
    public function updateCustomer(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'customer_id' => 'required',
                'customerName' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|numeric',
                'openingBalance' => 'nullable|numeric',
                'customer_email' => 'nullable',
            ]);

            $customer = Customers::find($validatedData['customer_id']);

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found!'], 404);
            }

            $customer->customer_name = $validatedData['customerName'];
            $customer->customer_address = $validatedData['address'];
            $customer->customer_phone = $validatedData['phone'];
            $customer->opening_balance = $validatedData['openingBalance'];
            $customer->customer_email = $validatedData['customer_email'];

            $customer->save();

            $responseData[] = [
                'customer_id' => $customer->customer_id,
                'customerName' => $customer->customer_name,
                'customer_email' => $customer->customer_email,
                'phone' => $customer->customer_phone,
                'address' => $customer->customer_address,
                'openingBalance' => $customer->opening_balance,
            ];

            return response()->json(['success' => true, 'message' => 'Customer updated successfully!', 'updated_customer' => $responseData], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // update customer

    // get customer
    public function getCustomers()
    {
        $user = Auth::user();
        $customers = Customers::where('company_id', $user->company_id)->where('branch_id', $user->user_branch)->get();

        $responseData = [];
        foreach ($customers as $customer) {
            $responseData[] = [
                'customer_id' => $customer->customer_id,
                'customerName' => $customer->customer_name,
                'customer_email' => $customer->customer_email,
                'phone' => $customer->customer_phone,
                'address' => $customer->customer_address,
                'openingBalance' => $customer->opening_balance,
            ];
        }

        return response()->json(['success' => true, 'data' => $responseData], 200);
    }
    // get customer

    // add customer
    public function addCustomer(Request $request)
    {
        try {

            $user = Auth::user();

            $validatedData = $request->validate([
                'customerName' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|numeric',
                'openingBalance' => 'nullable|numeric',
                'customer_email' => 'nullable',
            ]);

            $existingCustomer = Customers::where('company_id', $user->company_id)
                ->where('branch_id', $user->user_branch)
                ->where('customer_phone', $validatedData['phone'])
                ->first();

            if ($existingCustomer) {
                return response()->json(['success' => false, 'message' => 'Customer with this phone number already exists.'], 400);
            }

            $customer = Customers::create([
                'company_id' => $user->company_id,
                'branch_id' => $user->user_branch,
                'added_user_id' => $user->id,
                'customer_name' => $validatedData['customerName'],
                'customer_email' => $validatedData['customer_email'],
                'customer_phone' => $validatedData['phone'],
                'customer_address' => $validatedData['address'],
                'opening_balance' => $validatedData['openingBalance'],
            ]);

            if ($validatedData['openingBalance'] != null && $validatedData['openingBalance'] > 0) {
                $transaction = Trasanctions::create([
                    'company_id' => $user->company_id,
                    'branch_id' => $user->user_branch,
                    'added_user_id' => $user->id,
                    'customer_id' => $customer->customer_id,
                    'opening_balance' => $validatedData['openingBalance'],
                ]);
            }

            $responseData = [
                'customer_id' => $customer->customer_id,
                'customerName' => $customer->customer_name,
                'customer_email' => $customer->customer_email,
                'phone' => $customer->customer_phone,
                'address' => $customer->customer_address,
                'openingBalance' => $customer->opening_balance,
            ];

            return response()->json(['success' => true, 'message' => 'Customer added successfully!', 'added_customer' => $responseData], 200);
        } catch (\Exception $e) {
            return response()->json(['scuccess' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // add customer

    // get order
    public function getOrders(Request $request)
    {
        $user = Auth::user();

        // Get fromDate, toDate, and filterBy from the request
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $filterBy = $request->input('filterBy');

        // Query orders with filtering by date range and company_id
        $ordersQuery = Orders::with('order_items', 'additional_items')
            ->where('company_id', $user->company_id);

        // Apply date range filter if fromDate and toDate are provided
        if ($fromDate && $toDate) {
            if ($fromDate === $toDate) {
                // If fromDate and toDate are the same, include orders created on that specific date
                $ordersQuery->whereDate('created_at', $fromDate);
            } else {
                // If fromDate and toDate are different, use whereBetween
                $ordersQuery->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
            }
        }

        // Apply additional filters based on filterBy parameter
        if ($filterBy) {
            switch ($filterBy) {
                case 'today':
                    $ordersQuery->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $ordersQuery->whereDate('created_at', now()->subDay());
                    break;
                case 'last_three_days':
                    $ordersQuery->whereDate('created_at', '>=', now()->subDays(3)->startOfDay());
                    break;
                case 'last_week':
                    $ordersQuery->whereDate('created_at', '>=', now()->subWeek()->startOfDay());
                    break;
                case 'last_month':
                    $ordersQuery->whereDate('created_at', '>=', now()->subMonth()->startOfDay());
                    break;
                default:
                    // Do nothing for unknown filterBy values
                    break;
            }
        }

        // Fetch the filtered orders
        $orders = $ordersQuery->get();
        $ordersTotal = 0;
        foreach ($orders as $order) {
            $ordersTotal = $order->sum('order_final_total');
        }

        // Map the original response data to a new structure with changed variable names
        $mappedOrders = $orders->map(function ($order) {
            $cartItems = array_merge(
                $order->order_items->map(function ($item) {
                    $product = Products::find($item->product_id);
                    $category = null;
                    $kitchen = null;
                    if ($product) {
                        $category = ProductCategory::find($product->category_id);
                        if ($category) {
                            // Fetch kitchen details based on kitchen_id from the category
                            $kitchen = Kitchen::find($category->kitchen_id);
                        }
                    }
                    return [
                        'qty' => (int)$item->product_qty,
                        'price' => (int)$item->product_price,
                        'title' => $product->product_name,
                        'add_on' => json_decode($item->product_add_ons),
                        'variations' => json_decode($item->product_variations),
                        'product_id' => (int)$item->product_id,
                        'category' => $category->category_name,
                        'product_variation' => json_decode($item->product_variations),
                        'kitchen_id' => (int)$kitchen->kitchen_id,
                        'category_id' => (int)$category->category_id,
                        'branch_id' => (int)$product->branch_id,
                        'kitchen_name' => $kitchen->kitchen_name,
                        'category_name' => $category->category_name,
                        'product_code' => $product->product_code,
                        'favurite_item' => (int)$product->favourite_item,
                        'additional_item' => 0,
                    ];
                })->toArray(),
                $order->additional_items->map(function ($additionalItem) {
                    return [
                        'qty' => (int)$additionalItem->product_qty,
                        'price' => (int)$additionalItem->price,
                        'title' => $additionalItem->title,
                        'product_id' => (int)$additionalItem->product_id,
                        'additional_item' => 1,
                    ];
                })->toArray()
            );
            return [
                'info' => [
                    'phone' => $order->phone,
                    'customerName' => $order->customer_name,
                    'assignRider' => $order->assign_rider,
                    'address' => $order->customer_address,
                    'table_id' => $order->table_id,
                    'table_location' => $order->table_location,
                    'table_no' => $order->table_no,
                    'table_capacity' => $order->table_capacity,
                    'branch_id' => $order->branch_id,
                    'waiter' => $order->waiter_id,
                    'waiterName' => $order->waiter_name,
                ],
                'cartItems' => $cartItems,
                'type' => $order->order_type,
                'createdAt' => $order->order_no, // Assuming order_no is a numeric field
                'subTotal' => (int)$order->order_sub_total,
                'status' => $order->status,
                'userId' => (int)$order->added_user_id,
                'id' => (int)$order->order_id,
                'grandTotal' => (float)$order->order_grand_total, // Converted to float to maintain decimal points
                'finalTotal' => (float)$order->order_final_total, // Converted to float to maintain decimal points
                'discount' => (float)$order->order_discount, // Converted to float to maintain decimal points
                'change' => (float)$order->order_change, // Converted to float to maintain decimal points
                'split' => (int)$order->order_split,
                'isUploaded' => (int)$order->is_uploaded,
            ];
        });

        return response()->json(['success' => true, 'ordersTotal' => $ordersTotal, 'data' => $mappedOrders], 200);
    }

    // get order

    // create order
    public function createOrder(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'userId' => 'required|numeric',
                'id' => 'required|numeric',
                'createdAt' => 'required',
                'type' => 'required|string',
                'split' => 'nullable|numeric',
                'splittedAmount' => 'nullable|numeric',
                'subTotal' => 'required|numeric',
                'discount' => 'required|numeric',
                'saleTax' => 'nullable|numeric',
                'serviceCharges' => 'nullable|numeric',
                'change' => 'required|numeric',
                'status' => 'required|string',
                'finalTotal' => 'required|numeric',
                'grandTotal' => 'required|numeric',
                'cartItems' => 'required|array',
                'cartItems.*.product_id' => 'required',
                'cartItems.*.qty' => 'required|numeric',
                'cartItems.*.price' => 'required|numeric',
                'cartItems.*.product_variation' => 'nullable',
                'cartItems.*.title' => 'nullable',
                'cartItems.*.add_on' => 'nullable',
                'cartItems.*.additional_item' => 'required',
                'info.customerName' => 'nullable|string',
                'info.phone' => 'nullable|numeric',
                'info.assignRider' => 'nullable|numeric',
                'info.address' => 'nullable|string',
                'info.table_id' => 'nullable|numeric',
                'info.waiter' => 'nullable|numeric',
                'info.waiterName' => 'nullable|string',
                'info.table_location' => 'nullable|string',
                'info.table_no' => 'nullable|numeric',
                'info.table_capacity' => 'nullable|numeric',
                'info.branch_id' => 'nullable|numeric',
                'info.customer_id' => 'nullable|numeric',
                'credited_amount' => 'nullable',
            ]);

            $orderedUser = User::where('id', $validatedData['userId'])->first();

            DB::beginTransaction();

            $order = Orders::create([
                'added_user_id' => $validatedData['userId'],
                'order_id' => $validatedData['id'],
                'order_no' => $validatedData['createdAt'],
                'order_type' => $validatedData['type'],
                'order_sub_total' => $validatedData['subTotal'],
                'order_discount' => $validatedData['discount'],
                'order_grand_total' => $validatedData['grandTotal'],
                'order_final_total' => $validatedData['finalTotal'],
                'order_sale_tax' => $validatedData['saleTax'],
                'service_charges' => $validatedData['serviceCharges'],
                'order_change' => $validatedData['change'],
                'order_split' => $validatedData['split'],
                'order_split_amount' => $validatedData['splittedAmount'],
                'is_uploaded' => 1,
                'customer_name' => $validatedData['info']['customerName'],
                'phone' => $validatedData['info']['phone'],
                'assign_rider' => $validatedData['info']['assignRider'],
                'customer_address' => $validatedData['info']['address'],
                'table_id' => $validatedData['info']['table_id'],
                'table_location' => $validatedData['info']['table_location'],
                'table_no' => $validatedData['info']['table_no'],
                'table_capacity' => $validatedData['info']['table_capacity'],
                'branch_id' => $validatedData['info']['branch_id'],
                'waiter_id' => $validatedData['info']['waiter'],
                'waiter_name' => $validatedData['info']['waiterName'],
                'company_id' => $user->company_id,
                'user_branch_id' => $user->user_branch,
                'status' => $validatedData['status'],
                'customer_id' => $validatedData['info']['customer_id'],
            ]);

            foreach ($validatedData['cartItems'] as $cartItem) {
                if ($cartItem['additional_item'] == 1) {
                    OrderAdditionalItems::create([
                        'order_main_id' => $order->order_main_id,
                        'product_id' => $cartItem['product_id'],
                        'title' => $cartItem['title'],
                        'price' => $cartItem['price'],
                        'product_qty' => $cartItem['qty'],
                    ]);
                } else {
                    OrderItems::create([
                        'order_main_id' => $order->order_main_id,
                        'product_id' => $cartItem['product_id'],
                        'product_qty' => $cartItem['qty'],
                        'product_price' => $cartItem['price'],
                        'product_variations' => json_encode($cartItem['product_variation']),
                        'product_add_ons' => json_encode($cartItem['add_on']),
                    ]);
                }
            }
            if ($validatedData['credited_amount'] != null) {
                $transaction = Trasanctions::create([
                    'company_id' => $user->company_id,
                    'branch_id' => $user->user_branch,
                    'added_user_id' => $user->id,
                    'order_id' => $order->order_id,
                    'credit_amount' => $validatedData['credited_amount'],
                    'customer_id' => $validatedData['info']['customer_id'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order Created!', 'createdAt' => $order->order_no, 'isUploaded' => $order->is_uploaded, 'status' => $order->status], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // create order
    //----------------------------------------------------kitchen screen APIs------------------------------------------------------//

    //----------------------------------------------------kitchen screen APIs------------------------------------------------------//

    // delete table
    public function deleteTable($id)
    {
        $table = RestaurantTables::where('restaurant_table_id', $id)->first();

        $table->delete();

        return response()->json(['success' => true, 'message' => 'Table deleted', 'deleted_id' => $table->restaurant_table_id], 200);
    }
    // delete table

    // get table
    public function updateTable(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'id' => 'required',
                'table_no' => 'nullable|string',
                'table_location' => 'nullable|string',
                'table_capacity' => 'nullable|numeric',
                'branch_id' => 'nullable|numeric',
                'status' => 'nullable|string',
            ]);

            $table = RestaurantTables::where('restaurant_table_id', $validatedData['id'])->first();

            $table->table_no = $validatedData['table_no'];
            $table->table_location = $validatedData['table_location'];
            $table->table_capacity = $validatedData['table_capacity'];
            $table->branch_id = $validatedData['branch_id'];
            $table->status = $validatedData['status'];

            $table->save();

            $table->id = $table->restaurant_table_id;
            unset($table->restaurant_table_id);

            return response()->json(['success' => true, 'message' => 'Table Updated!', 'data' => $table], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // get table

    // get table
    public function getTables()
    {
        $user = Auth::user();

        if ($user->user_role == 'admin') {
            $tables = RestaurantTables::where('company_id', $user->company_id)->get();
        } else {
            $tables = RestaurantTables::where(['compnay_id', $user->company_id, 'branch_id' => $user->user_branch])->get();
        }

        // Use map to rename the column
        $tables = $tables->map(function ($table) {
            $table->id = $table->restaurant_table_id;
            unset($table->restaurant_table_id); // Remove the old column
            return $table;
        });

        return response()->json(['success' => true, 'data' => $tables], 200);
    }
    // get table

    // add table
    public function addTable(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'table_no' => 'required|string',
                'table_location' => 'required|string',
                'table_capacity' => 'required|numeric',
                'branch_id' => 'required|numeric',
            ]);

            $table = RestaurantTables::create([
                'company_id' => $user->company_id,
                'branch_id' => $validatedData['branch_id'],
                'table_no' => $validatedData['table_no'],
                'table_capacity' => $validatedData['table_capacity'],
                'table_location' => $validatedData['table_location'],
                'status' => 'available',
            ]);

            $table->id = $table->restaurant_table_id;
            unset($table->restaurant_table_id);

            return response()->json(['success' => true, 'message' => 'Table added!', 'data' => $table], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // add table
    //----------------------------------------------------kitchen screen APIs------------------------------------------------------//

    //----------------------------------------------------kitchen screen APIs------------------------------------------------------//
    // get kicthen
    public function updateKitchen(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'kitchen_id' => 'required',
                'kitchen_name' => 'required|string',
                'printer_ip' => 'required|string',
                'branch_id' => 'required|numeric',
            ]);

            $kitchen = Kitchen::where('kitchen_id', $validatedData['kitchen_id'])->first();

            if (!$kitchen) {
                return response()->json(['success' => false, 'message' => 'No kitchen found!'], 404);
            }

            $kitchen->kitchen_name = $validatedData['kitchen_name'];
            $kitchen->printer_ip = $validatedData['printer_ip'];
            $kitchen->branch_id = $validatedData['branch_id'];

            $kitchen->save();

            return response()->json(['success' => true, 'message' => 'Kitchen updated!', 'data' => $kitchen], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // get kicthen

    // get kicthen
    public function deleteKitchen($id)
    {
        try {
            $user = Auth::user();

            $kitchen = Kitchen::where('kitchen_id', $id)->first();

            if (!$kitchen) {
                return response()->json(['success' => false, 'message' => 'No kitchen found!'], 404);
            }

            $kitchen->delete();

            return response()->json(['success' => true, 'message' => 'Kitchen deleted!', 'deleted_id' => $kitchen->kitchen_id], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // get kicthen

    // get kicthen
    public function getKitchen()
    {
        $user = Auth::user();
        if ($user->user_role == 'admin') {
            $kitchen = Kitchen::where(['company_id' => $user->company_id])->get();
        } else {
            $kitchen = Kitchen::where(['company_id' => $user->company_id, 'branch_id' => $user->user_branch])->get();
        }

        return response()->json(['success' => true, 'data' => $kitchen], 200);
    }
    // get kicthen

    // add kicthen
    public function addKitchen(Request $request)
    {
        $user = Auth::user();
        try {
            $validatedData = $request->validate([
                'kitchen_name' => 'required|string',
                'printer_ip' => 'required|string',
                'branch_id' => 'required|numeric',
            ]);

            $kitchen = Kitchen::create([
                'company_id' => $user->company_id,
                'kitchen_name' => $validatedData['kitchen_name'],
                'printer_ip' => $validatedData['printer_ip'],
                'branch_id' => $validatedData['branch_id'],
            ]);

            return response()->json(['success' => true, 'message' => 'kitchen added!', 'data' => $kitchen], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // add kicthen
    //----------------------------------------------------kitchen screen APIs------------------------------------------------------//

    //----------------------------------------------------Branch APIs------------------------------------------------------//
    // update branch
    public function updateBranch(Request $request, $id)
    {
        $user = Auth::user();
        try {

            $validatedData = $request->validate([
                'branch_name' => 'required|string',
                'branch_phone' => 'required|string',
                'branch_address'  => 'required|string',
                'branch_manager' => 'required|string',
            ]);

            $branch = CompanyBranch::where('branch_id', $id)->where('company_id', $user->company_id)->first();

            if (!$branch) {
                return response()->json(['success' => false, 'message' => 'no  branch found'], 404);
            }

            $branch->branch_name = $validatedData['branch_name'];
            $branch->branch_phone = $validatedData['branch_phone'];
            $branch->branch_address = $validatedData['branch_address'];
            $branch->branch_manager = $validatedData['branch_manager'];

            $branch->save();

            $branch->branch_status = ($branch->branch_status ==  1) ? 'Active' : 'Inactive';

            return  response()->json(['success' => true, 'message' => 'branch updated successfully!', 'data' => $branch], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // update branch
    // get branch
    public function getBranches()
    {
        $user = Auth::user();
        try {
            $branches = CompanyBranch::where('company_id', $user->company_id)->orderBy('branch_id', 'desc')->get();

            if (!$branches) {
                return response()->json(['success' => false, 'message' => 'No branches found'], 400);
            }

            foreach ($branches as $branch) {
                $branch->branch_status = ($branch->branch_status == 1) ? 'Active' : 'Inactive';
            }

            return response()->json(['success' => true, 'data' => ['branches' => $branches]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // get branch

    // add branch
    public function deleteBranch(Request $request, $id)
    {
        $user = Auth::user();
        try {
            $branch = CompanyBranch::where('branch_id', $id)->where('company_id', $user->company_id)->first();

            if (!$branch) {
                return response()->json(['success' => false, 'message' => 'No branch found!'], 404);
            }

            $branch->branch_status = 0;

            $branch->save();

            $branch->branch_status = ($branch->branch_status == 1) ? 'Active' : 'Inactive';

            return response()->json(['success' => true, 'message' => 'branch deleted successfully!', 'data' => ['deleted_branch' => $branch]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // add branch

    // add branch
    public function addBranch(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'branch_code' => 'required|string',
                'branch_name' => 'required|string',
                'branch_email' => 'required|string',
                'branch_phone' => 'required|numeric',
                'branch_address' => 'required|string',
                'branch_manager' => 'required|string',
            ]);

            $existingBranch = CompanyBranch::where([
                'company_id' => $user->company_id,
                'branch_code' => $validatedData['branch_code'],
            ])->first();

            if ($existingBranch) {
                return response()->json(['success' => false, 'message' => 'The branch code already exists for this company'], 400);
            }

            $branch = CompanyBranch::create([
                'company_id' => $user->company_id,
                'branch_code' => $validatedData['branch_code'],
                'branch_name' => $validatedData['branch_name'],
                'branch_email' => $validatedData['branch_email'],
                'branch_phone' => $validatedData['branch_phone'],
                'branch_address' => $validatedData['branch_address'],
                'branch_manager' => $validatedData['branch_manager'],
            ]);

            $addedBranch = CompanyBranch::find($branch->branch_id);

            $addedBranch->branch_status = ($addedBranch->branch_status == 1) ? 'Active' : 'Inactive';

            return response()->json(['success' => true, 'message' => 'Branch created successfully!', 'data' => ['added_branch' => $addedBranch]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // add branch
    //----------------------------------------------------Branch APIs------------------------------------------------------//

    //----------------------------------------------------product APIs------------------------------------------------------//
    public function updateProduct(Request $request, $id)
    {
        $user = Auth::user();

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'product_code' => 'required|string',
                'product_name' => 'required|string',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'product_price' => 'nullable|numeric',
                'category_id' => 'required|numeric',
                'branch_id' => 'required|numeric',
                'product_variation' => 'nullable|array',
                'product_variation.*.variation_name' => 'nullable|string',
                'product_variation.*.variation_price' => 'nullable|string',
                'product_addOn' => 'nullable|array',
                'product_addOn.*.addOn_name' => 'nullable|string',
                'product_addOn.*.addOn_price' => 'nullable|numeric',
                'favourite_item' => 'nullable|numeric',
            ]);

            // Find the existing product by ID
            $existingProduct = Products::where('product_id', $id)->where('company_id', $user->company_id)->first();

            // If the product does not exist, return an error response
            if (!$existingProduct || $existingProduct->company_id !== $user->company_id) {
                return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
            }

            // Update the existing product
            $existingProduct->fill([
                'product_code' => $validatedData['product_code'],
                'product_name' => $validatedData['product_name'],
                'product_price' => $validatedData['product_price'],
                'category_id' => $validatedData['category_id'],
                'branch_id' => $validatedData['branch_id'],
                'app_url' => $this->appUrl,
                'favourite_item' => $validatedData['favourite_item'],
            ]);

            // Handle product image update
            if ($request->hasFile('upload_image')) {
                $image = $request->file('upload_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/product_images', $imageName); // Adjust storage path as needed
                $existingProduct->product_image = 'storage/product_images/' . $imageName;
            }

            $existingProduct->save();

            // Delete existing variations and add-ons
            $existingProduct->variations()->delete();
            $existingProduct->add_ons()->delete();

            // Add new variations
            if (isset($validatedData['product_variation']) && is_array($validatedData['product_variation'])) {
                foreach ($validatedData['product_variation'] as $variation) {
                    if ($variation['variation_name'] !== null || $variation['variation_price'] !== null) {
                        $existingProduct->variations()->create($variation);
                    }
                }
            }

            // Add new add-ons
            if (isset($validatedData['product_addOn']) && is_array($validatedData['product_addOn'])) {
                foreach ($validatedData['product_addOn'] as $addOn) {
                    if ($addOn['addOn_name'] !== null || $addOn['addOn_price'] !== null) {
                        $existingProduct->add_ons()->create($addOn);
                    }
                }
            }

            // Fetch the updated product with details
            $updatedProduct = Products::with('variations', 'add_ons', 'category')->find($existingProduct->product_id);
            $kitchen = Kitchen::where('kitchen_id', $updatedProduct->category->kitchen_id)->first();

            return response()->json(['success' => true, 'message' => 'Product updated successfully!', 'data' => [
                'updated_product' => [
                    'product_id' => $updatedProduct->product_id,
                    'company_id' => $updatedProduct->company_id,
                    'branch_id' => $updatedProduct->branch_id,
                    'category_id' => $updatedProduct->category_id,
                    'category' => $updatedProduct->category->category_name,
                    'printer_ip' => $kitchen->printer_ip,
                    'kitchen_id' => $kitchen->kitchen_id,
                    'kitchen_name' => $kitchen->kitchen_name,
                    'product_code' => $updatedProduct->product_code,
                    'title' => $updatedProduct->product_name,
                    'product_image' => $updatedProduct->product_image,
                    'app_url' => $updatedProduct->app_url,
                    'favourite_item' => $updatedProduct->favourite_item,
                    'price' => $updatedProduct->product_price,
                    'created_at' => $updatedProduct->created_at,
                    'updated_at' => $updatedProduct->updated_at,
                    'variations' => $updatedProduct->variations,
                    'add_on' => $updatedProduct->add_ons->map(function ($addOn) {
                        return [
                            'addOn_id' => $addOn->addOn_id,
                            'product_id' => $addOn->product_id,
                            'addOn_name' => $addOn->addOn_name,
                            'addOn_price' => $addOn->addOn_price,
                            'created_at' => $addOn->created_at,
                            'updated_at' => $addOn->updated_at,
                        ];
                    }),
                ],
            ],], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //deletee Product
    public function deleteProduct($id)
    {
        try {
            $product = Products::find($id);

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'No products found!'], 404);
            }

            // Delete associated overheads.
            $product->variations()->delete();
            $product->add_ons()->delete();

            $path = 'storage/product_images/' . $product->product_image;

            if (File::exists($path)) {
                File::delete($path);
            }

            $product->delete();

            return response()->json(['success' => true, 'message' => 'product deleted successfully!'], 200);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 200);
        }
    }
    //deletee Product

    //add Product
    public function getProducts()
    {
        $user = Auth::user();

        try {
            $products = Products::with(['variations', 'add_ons'])
                ->where('company_id', $user->company_id)
                ->orderBy('product_id', 'desc')
                ->get();

            if ($products->count() > 0) {
                $formattedProducts = $products->map(function ($product) {
                    $category = ProductCategory::find($product->category_id);

                    // Fetch kitchen information based on kitchen_id
                    $kitchen = Kitchen::where('kitchen_id', $category->kitchen_id)->first();

                    return [
                        'product_id' => $product->product_id,
                        'company_id' => $product->company_id,
                        'branch_id' => $product->branch_id,
                        'category_id' => $product->category_id,
                        'category' => $category ? $category->category_name : null,
                        'printer_ip' => $kitchen->printer_ip,
                        'kitchen_id' => $kitchen->kitchen_id,
                        'kitchen_name' => $kitchen->kitchen_name,
                        'product_code' => $product->product_code,
                        'title' => $product->product_name,
                        'product_image' => $product->product_image,
                        'favourite_item' => $product->favourite_item,
                        'app_url' => $product->app_url,
                        'price' => $product->product_price,
                        'created_at' => $product->created_at,
                        'updated_at' => $product->updated_at,
                        'variations' => $product->variations,
                        'add_on' => $product->add_ons->map(function ($addOn) {
                            return [
                                'addOn_id' => $addOn->addOn_id,
                                'product_id' => $addOn->product_id,
                                'addOn_name' => $addOn->addOn_name,
                                'addOn_price' => $addOn->addOn_price,
                                'created_at' => $addOn->created_at,
                                'updated_at' => $addOn->updated_at,
                            ];
                        }),
                    ];
                });

                return response()->json(['success' => true, 'data' => ['products' => $formattedProducts]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No products found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //add Product

    //add Product
    public function addProduct(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'product_code' => 'required|string',
                'product_name' => 'required|string',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'product_price' => 'nullable|numeric',
                'category_id' => 'required|numeric',
                'branch_id' => 'required|numeric',
                'product_variation' => 'nullable|array',
                'product_variation.*.variation_name' => 'nullable|string',
                'product_variation.*.variation_price' => 'nullable|string',
                'product_addOn' => 'nullable|array',
                'product_addOn.*.addOn_name' => 'nullable|string',
                'product_addOn.*.addOn_price' => 'nullable|numeric',
            ]);

            $existingProduct = Products::where('product_name', $validatedData['product_name'])
                ->where('product_code', $validatedData['product_code'])
                ->where('company_id', $user->company_id)
                ->first();

            if ($existingProduct) {
                return response()->json(['success' => false, 'message' => 'Product with the same name and code already exists.'], 400);
            }

            $product = Products::create([
                'product_code' => $validatedData['product_code'],
                'product_name' => $validatedData['product_name'],
                'product_price' => $validatedData['product_price'],
                'category_id' => $validatedData['category_id'],
                'company_id' => $user->company_id,
                'branch_id' => $validatedData['branch_id'],
                'app_url' => $this->appUrl,
            ]);

            if ($request->hasFile('upload_image')) {
                $image = $request->file('upload_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/product_images', $imageName); // Adjust storage path as needed
                $product->product_image = 'storage/product_images/' . $imageName;
            }

            $product->save();

            if (isset($validatedData['product_variation']) && is_array($validatedData['product_variation'])) {
                foreach ($validatedData['product_variation'] as $variation) {
                    if ($variation['variation_name'] !== null || $variation['variation_price'] !== null) {
                        $product->variations()->create($variation);
                    }
                }
            }

            // Check if product_addOn is present before iterating
            if (isset($validatedData['product_addOn']) && is_array($validatedData['product_addOn'])) {
                // Create product add-ons
                foreach ($validatedData['product_addOn'] as $addOn) {
                    if ($addOn['addOn_name'] !== null || $addOn['addOn_price'] !== null) {
                        $product->add_ons()->create($addOn);
                    }
                }
            }
            $addedProduct = Products::with('variations', 'add_ons', 'category')->find($product->product_id);

            $kitchen = Kitchen::where('kitchen_id', $addedProduct->category->kitchen_id)->first();

            return response()->json(['success' => true, 'message' => 'product added successfully!', 'data' => [
                'added_product' => [
                    'product_id' => $addedProduct->product_id,
                    'company_id' => $addedProduct->company_id,
                    'branch_id'  => $addedProduct->branch_id,
                    'category_id' => $addedProduct->category_id,
                    'category' => $addedProduct->category->category_name,
                    'printer_ip' => $addedProduct->category->printer_ip,
                    'kitchen_id' => $kitchen->kitchen_id,
                    'kitchen_name' => $kitchen->kitchen_name,
                    'product_code' => $addedProduct->product_code,
                    'title' => $addedProduct->product_name,  // Rename product_name to title
                    'product_image' => $addedProduct->product_image,
                    'favourite_item' => $addedProduct->favourite_item,
                    'app_url' => $addedProduct->app_url,
                    'price' => $addedProduct->product_price,
                    'created_at' => $addedProduct->created_at,
                    'updated_at' => $addedProduct->updated_at,
                    'variations' => $addedProduct->variations,
                    'add_on' => $addedProduct->add_ons->map(function ($addOn) {
                        return [
                            'addOn_id' => $addOn->id,
                            'product_id' => $addOn->product_id,
                            'addOn_name' => $addOn->addOn_name, // Change addOn_name to addon_title
                            'addOn_price' => $addOn->addOn_price,
                            'created_at' => $addOn->created_at,
                            'updated_at' => $addOn->updated_at,
                        ];
                    }),
                ],
            ],], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //add Product
    //----------------------------------------------------product APIs------------------------------------------------------//
    //----------------------------------------------------product category APIs------------------------------------------------------//

    //updte product category
    public function deleteCategory($id)
    {
        $category = ProductCategory::where('category_id', $id)->first();

        $category->delete();

        return response()->json(['success' => true, 'message' => 'category deleted!'], 200);
    }
    //updte product category

    //updte product category
    public function updateProductCategory(Request $request)
    {
        try {
            $user = Auth::user();
            $validatedData = $request->validate([
                'category_id' => 'required',
                'category_name' => 'nullable|string',
                // 'printer_ip' => 'nullable|string',
                'branch_id' => 'nullable|string',
                'kitchen_id' => 'nullable|numeric',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);

            $category = ProductCategory::where('category_id', $validatedData['category_id'])->first();

            $category->category_name = $validatedData['category_name'];
            // $category->printer_ip = $validatedData['printer_ip'];
            $category->branch_id = $validatedData['branch_id'];
            $category->kitchen_id = $validatedData['kitchen_id'];

            $category->save();

            return response()->json(['success' => true, 'message' => 'Category Updated successfully!', 'data' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //updte product category

    //get product category
    public function getProductCategory()
    {
        $user = Auth::user();

        try {
            // $productCategories = ProductCategory::where(function ($query) use ($user) {
            //     $query->where('company_id', $user->company_id)
            //         ->where('branch_id', $user->user_branch);
            // })->orWhere(function ($query) {
            //     $query->where('branch_id', 'all');
            // })->orderBy('category_id', 'desc')->get();
            // Old one 

            $productCategories = ProductCategory::where(function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })->orderBy('category_name', 'asc')->get();



            if ($productCategories->count() > 0) {
                return response()->json(['success' => true, 'data' => ['product_categories' => $productCategories]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No categories found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //get product category

    //add product category
    public function addProductCategory(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'category_name' => 'required|string',
                // 'printer_ip' => 'required|string',
                'branch_id' => 'required|string',
                'kitchen_id' => 'required|numeric',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);

            // Check if the category already exists for the given company_id
            $category = ProductCategory::firstOrCreate(
                [
                    'company_id' => $user->company_id,
                    'category_name' => $validatedData['category_name'],
                ],
                [
                    // 'printer_ip' => $validatedData['printer_ip'],
                    'branch_id' => $validatedData['branch_id'],
                    'kitchen_id' => $validatedData['kitchen_id'],
                    'app_url' => $this->appUrl,
                ]
            );

            if ($request->hasFile('upload_image')) {
                $image = $request->file('upload_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/category_images', $imageName); // Adjust storage path as needed
                $category->category_image = 'storage/category_images/' . $imageName;
            }

            $category->save(); // Save the model after updating the image field

            $addedCategory = ProductCategory::where('category_id', $category->category_id)->get();

            if ($category->wasRecentlyCreated) {
                return response()->json(['success' => true, 'message' => 'Product Category added successfully!', 'data' => ['added_category' => $addedCategory]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Category with the same name already exists for this company.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //add product category

    //----------------------------------------------------product category APIs------------------------------------------------------//

    //----------------------------------------------------company APIs------------------------------------------------------//
    //get expenses
    public function getCompanyExpenses(Request $request)
    {
        $user = Auth::user();

        try {
            // Get the 'expenseDate' query parameter from the URL
            $expenseDate = $request->query('expenseDate');

            if ($expenseDate) {
                // Validate the 'expenseDate' format (optional, based on your requirements)
                // You can use Carbon or other date handling libraries for more advanced date validation.

                // Retrieve expenses for the user's company filtered by 'expenseDate'
                $expenses = CompanyExpense::where('company_id', $user->company_id)
                    ->whereDate('expense_date', $expenseDate)
                    ->orderBy('expense_id', 'desc')
                    ->get();

                if ($expenses->isEmpty()) {
                    return response()->json(['success' => false, 'message' => 'No expenses found for the company on the specified date'], 404);
                }
            } else {
                // If 'expenseDate' is not provided, retrieve all expenses for the user's company
                $expenses = CompanyExpense::where('company_id', $user->company_id)->orderBy('expense_id', 'desc')->get();
            }

            // Format 'expense_date' in the response
            $formattedExpenses = $expenses->map(function ($expense) {
                $expense->expense_date = Carbon::parse($expense->expense_date)->format('d M Y');
                return $expense;
            });

            return response()->json(['success' => true, 'data' => ['expenses' => $formattedExpenses]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }



    //get expenses
    //company expense
    public function addExpense(Request $request)
    {
        $user = Auth::user();
        try {
            $validatedData = $request->validate([
                'expense_date' => 'required|date',
                'expenses' => 'required|array', // Expenses should be an array
                'expenses.*.expense_name' => 'required|string',
                'expenses.*.expense_cost' => 'required|numeric',
            ]);

            // Extract the expense date from the request
            $expenseDate = $validatedData['expense_date'];

            $expenses = $validatedData['expenses'];

            // Create an array to store the newly created expenses
            $createdExpenses = [];

            foreach ($expenses as $expenseData) {
                $expense = CompanyExpense::create([
                    'company_id' => $user->company_id,
                    'added_user_id' => $user->id,
                    'expense_date' => $expenseDate, // Set the common expense date
                    'expense_name' => $expenseData['expense_name'],
                    'expense_cost' => $expenseData['expense_cost'],
                ]);

                $createdExpenses[] = $expense;
            }

            return response()->json(['success' => true, 'message' => 'Expenses added successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }
    //company expense
    //add company soial links
    public function addCompanyLinks(Request $request)
    {
        $user = Auth::user();
        $company = Company::find($user->company_id);
        try {

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'Company not found!'], 200);
            }
            $validatedData = $request->validate([
                'fb_acc' => 'nullable|string',
                'ig_acc' => 'nullable|string',
                'tt_acc' => 'nullable|string',
            ]);

            $company->fb_acc = $validatedData['fb_acc'];
            $company->ig_acc = $validatedData['ig_acc'];
            $company->tt_acc = $validatedData['tt_acc'];

            $company->save();

            return response()->json(['success' => true, 'message' => "Social links added to your company's profile!"], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //add company soial links
    //----------------------------------------------------company APIs------------------------------------------------------//
    //----------------------------------------------------Image APIs------------------------------------------------------//
    //get feed
    public function getFeed(Request $request)
    {
        $user = Auth::user();
        try {
            $companyId = $user->company_id;

            $feed = imageGallery::where('company_id', $companyId)->inRandomOrder()->take(20)->get();

            $feed = $feed->map(function ($item) {
                $item['customer_name'] = Customers::where('customer_id', $item->customer_id)->value('customer_name');
                $item['staff_name'] = User::where('id', $item->staff_id)->value('name');
                return $item;
            });
            if (!$feed) {
                return response()->json(['success' => false, 'message' => 'No data found in the feed!'], 404);
            }

            return response()->json(['success' => true, 'data' => ['feed' => $feed]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //get feed

    //post image
    public function getMedia(Request $request)
    {
        $user = Auth::user();
        try {
            $companyId = $user->company_id;

            $customerId = $request->input('customerId');
            $images = imageGallery::where(['customer_id' => $customerId, 'company_id' => $companyId])->get();

            if ($images->count() === 0) {
                return response()->json(['success' => false, 'message' => 'No media found of this customer'], 404);
            }

            return response()->json(['success' => true, 'data' => ['media' => $images]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //post image

    //post image
    public function postMedia(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'staff_id' => 'nullable|numeric',
                'customer_id' => 'required|numeric',
                'order_id' => 'nullable|numeric',
                'media_type' => 'required|string',
                'upload_media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi', // Allow any size for videos
            ]);

            $postMedia = new imageGallery([
                'customer_id' => $validatedData['customer_id'],
                'company_id' => $user->company_id,
                'order_id' => $validatedData['order_id'],
                'staff_id' => $validatedData['staff_id'],
                'media_type' => $validatedData['media_type'],
                'added_user_id' => $user->id,
                'app_url' => $this->appUrl,
            ]);

            if ($request->hasFile('upload_media')) {
                $media = $request->file('upload_media');
                $mediaExtension = $media->getClientOriginalExtension();
                $mediaName = time() . '.' . $mediaExtension;
                $storagePath = $mediaExtension === 'mp4' || $mediaExtension === 'mov' || $mediaExtension === 'avi' ?
                    'public/video_gallery' : 'public/image_gallery';

                $media->storeAs($storagePath, $mediaName);

                $postMedia->stored_media = 'storage/' . ($mediaExtension === 'mp4' || $mediaExtension === 'mov' || $mediaExtension === 'avi' ?
                    'video_gallery' : 'image_gallery') . '/' . $mediaName;
            }

            $postMedia->save();

            return response()->json(['success' => true, 'message' => 'Media uploaded successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //post image
    //----------------------------------------------------Image APIs------------------------------------------------------//



    //----------------------------------------------------Service APIs------------------------------------------------------//
    //get service detail
    public function getServicedetail(Request $request)
    {
        try {
            $user = Auth::user();
            $serviceId = $request->input('serviceId'); // Get the 'serviceId' parameter from the request

            // Start by retrieving all services that belong to the user's company
            $query = Services::where('company_id', $user->company_id);

            // If a 'serviceId' parameter is provided, filter services by 'service_name'
            if (!empty($serviceId)) {
                $query->where('service_id', $serviceId);
            }

            // Retrieve the filtered services
            $services = $query->get();

            // Initialize an empty array to store the final response data
            $responseData = [];

            // Retrieve all data from the 'service_overheads' table
            $allServiceOverheads = ServiceOverheads::all();

            // Iterate through each service to fetch its associated overheads
            foreach ($services as $service) {
                $serviceId = $service->service_id;

                // Filter service overheads by service_id
                $overheads = $allServiceOverheads->where('service_id', $serviceId)->toArray();

                // Calculate the total overhead cost for this service
                $totalCost = array_sum(array_column($overheads, 'overhead_cost'));

                // Add 'service_overheads' and 'total_overhead_cost' to the 'service' object
                $service->service_overheads = array_values($overheads); // Re-index the array
                // $service->total_overhead_cost = $totalCost;

                // Add the service data to the response array
                $responseData = $service;
            }

            if (!empty($responseData)) {
                return response()->json(['success' => true, 'data' => ['services' => $responseData]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No services found!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //get service detail

    //get service
    public function getService(Request $request)
    {
        try {
            $user = Auth::user();
            $search = $request->input('search'); // Get the 'search' parameter from the request

            // Start by retrieving all services that belong to the user's company
            $query = Services::where('company_id', $user->company_id);

            // If a 'search' parameter is provided, filter services by 'service_name'
            if (!empty($search)) {
                $query->where('service_name', 'like', '%' . $search . '%');
            }
            $query->orderBy('service_id', 'desc');
            // Retrieve the filtered services
            $services = $query->get();

            // Initialize an empty array to store the final response data
            $responseData = [];

            // Retrieve all data from the 'service_overheads' table
            $allServiceOverheads = ServiceOverheads::all();

            // Iterate through each service to fetch its associated overheads
            foreach ($services as $service) {
                $serviceId = $service->service_id;

                // Filter service overheads by service_id
                $overheads = $allServiceOverheads->where('service_id', $serviceId)->toArray();

                // Calculate the total overhead cost for this service
                $totalCost = array_sum(array_column($overheads, 'overhead_cost'));

                // Add 'service_overheads' and 'total_overhead_cost' to the 'service' object
                $service->service_overheads = array_values($overheads); // Re-index the array
                // $service->total_overhead_cost = $totalCost;

                // Add the service data to the response array
                $responseData[] = $service;
            }

            if (!empty($responseData)) {
                return response()->json(['success' => true, 'data' => ['services' => $responseData]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No services found!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }


    //get service

    //delete service
    public function deleteService($id)
    {
        try {
            $service = Services::find($id);

            if (!$service) {
                return response()->json(['success' => false, 'message' => 'No services found!'], 404);
            }

            // Delete associated overheads.
            $service->overheads()->delete();

            $path = 'storage/service_images/' . $service->service_image;

            if (File::exists($path)) {
                File::delete($path);
            }

            $service->delete();

            return response()->json(['success' => true, 'message' => 'Service deleted successfully!'], 200);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 200);
        }
    }
    //delete service

    //update service
    public function updateService(Request $request, $id)
    {
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'subtitle' => 'required|string|max:255',
                'charges' => 'required|numeric',
                'description' => 'required|string|max:400',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'service_duration' => 'required|numeric',
                // 'added_user_id' => 'required',
                // 'company_id' => 'required',
                'overheads' => 'array', // Define 'overheads' as an array
                // 'overheads.*.cost_name' => 'required|string|max:255',
                // 'overheads.*.cost' => 'required|numeric',
            ]);

            // Find the service to be updated
            $service = Services::find($id);

            if (!$service) {
                return response()->json(['success' => false, 'message' => 'No service found!'], 404);
            }
            $user = Auth::user();
            // Update the service data
            $service->service_name = $validatedData['name'];
            $service->service_subtitle = $validatedData['subtitle'];
            $service->service_charges = $validatedData['charges'];
            $service->service_desc = $validatedData['description'];
            $service->service_duration = $validatedData['service_duration'];
            $service->added_user_id = $user->id;
            $service->company_id = $user->company_id;
            $service->app_url = $this->appUrl;

            // Upload and store the updated service image
            if ($request->hasFile('upload_image')) {
                $image = $request->file('upload_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/service_images', $imageName); // Adjust storage path as needed
                $service->service_image = 'storage/service_images/' . $imageName;
            }

            $service->save();

            // Delete existing overhead data for the service
            ServiceOverheads::where('service_id', $id)->delete();

            // Insert updated overhead data into the 'services_overheads' table for the service
            if (!empty($request['cost_name'])) {
                $overheads = $request['cost_name'];
                $count = count($overheads);

                for ($i = 0; $i < $count; $i++) {
                    ServiceOverheads::create([
                        'service_id' => $service->service_id,
                        'overhead_name' => $_REQUEST['cost_name'][$i],
                        'overhead_cost' => $_REQUEST['cost'][$i],
                    ]);
                }
            }

            // Optionally, you can redirect back with a success message
            return response()->json(['success' => true, 'message' => 'Service updated successfully!']);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    //update service


    //add service
    public function addService(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the incoming JSON data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'subtitle' => 'required|string|max:255',
                'charges' => 'required|numeric',
                'description' => 'required|string|max:400',
                'service_duration' => 'required|numeric',
                'servicesOverheads' => 'required|array',
                'servicesOverheads.*.cost_name' => 'nullable|string',
                'servicesOverheads.*.cost' => 'nullable|numeric',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);
            $existingService = Services::where('service_name', $validatedData['name'])
                ->where('company_id', $user->company_id)
                ->first();

            if ($existingService) {
                return response()->json(['success' => false, 'message' => 'Service with the same name already exists'], 400);
            }
            // Create a new service
            $service = new Services([
                'service_name' => $validatedData['name'],
                'service_subtitle' => $validatedData['subtitle'],
                'service_charges' => $validatedData['charges'],
                'service_desc' => $validatedData['description'],
                'service_duration' => $validatedData['service_duration'],
                'added_user_id' => $user->id,
                'company_id' => $user->company_id,
                'app_url' => $this->appUrl,
            ]);

            // Upload and store the service image if it exists
            if ($request->hasFile('upload_image')) {
                $image = $request->file('upload_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/service_images', $imageName); // Adjust storage path as needed
                $service->service_image = 'storage/service_images/' . $imageName;
            }

            $service->save();

            // Insert overhead data into the 'services_overheads' table for the service
            if (!empty($validatedData['servicesOverheads'])) {
                foreach ($validatedData['servicesOverheads'] as $overhead) {
                    if ($overhead['cost_name'] !== null || $overhead['cost'] !== null) {
                        $overheadData = [
                            'service_id' => $service->service_id,
                            'overhead_name' => $overhead['cost_name'] ?? 'Default Name',
                            'overhead_cost' => $overhead['cost'] ?? 0.0,
                        ];

                        ServiceOverheads::create($overheadData);
                    }
                }
            }


            // Return a success response
            return response()->json(['success' => true, 'message' => 'Service added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //add service

    //----------------------------------------------------Service APIs------------------------------------------------------//

    //----------------------------------------------------Staff APIs------------------------------------------------------//
    //get staff detail
    public function getStaffDetail(Request $request)
    {
        try {
            $user = Auth::user();
            $staffId = $request->input('staffId');
            $includeCustomers = $request->input('includeCustomers', false);

            $query = User::where('user_role', '2')->where('company_id', $user->company_id);

            if (!empty($staffId)) {
                $query->where('id', $staffId);
            }

            $staff = $query->first();

            if (!$staff) {
                return response()->json(['success' => false, 'message' => 'No staff found!'], 404);
            }

            // Split social links into individual values
            $socialLinks = explode(',', $staff->social_links);

            $staffData = [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'email_verified_at' => $staff->email_verified_at,
                'created_at' => $staff->created_at,
                'updated_at' => $staff->updated_at,
                'company_id' => $staff->company_id,
                'phone' => $staff->phone,
                'address' => $staff->address,
                'category' => $staff->category,
                'user_image' => $staff->user_image,
                'fb_acc' => isset($socialLinks[0]) ? $socialLinks[0] : null,
                'ig_acc' => isset($socialLinks[1]) ? $socialLinks[1] : null,
                'tt_acc' => isset($socialLinks[2]) ? $socialLinks[2] : null,
                'user_role' => $staff->user_role,
                'app_url' => $staff->app_url,
                'otp' => $staff->otp,
            ];

            $response = ['success' => true, 'data' => ['staff' => $staffData]];

            if ($includeCustomers) {
                $customers = Customers::where('staff_id', $staffId)->get();
                $response['data']['customers'] = $customers;
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //get staff detail
    //get staff
    public function getStaff(Request $request)
    {
        try {
            $user = Auth::user();
            $search = $request->input('search'); // Get the 'search' parameter from the request

            $userRoles = ['manager', 'cashier', 'chef', 'waiter', 'rider'];

            $query = User::whereIn('user_role', $userRoles)->where('company_id', $user->company_id);

            // If a 'search' parameter is provided, filter staff by user name
            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }
            $query->orderBy('id', 'desc');

            $staff = $query->select('id', 'name', 'email', 'password', 'company_id', 'phone', 'address', 'category', 'user_image', 'user_role', 'user_status', 'user_priviledges', 'user_branch', 'app_url', 'country', 'state', 'city', 'language', 'zip_code')->get();
            $staff->transform(function ($staffMember) {
                $staffMember['user_priviledges'] = json_decode($staffMember['user_priviledges']);
                return $staffMember;
            });
            if ($staff->count() > 0) {
                return response()->json(['success' => true, 'data' => ['staff' => $staff]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No staff found!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //get staff

    //delete staff
    public function deleteStaff($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No staff found!'], 404);
            }

            $path = 'storage/staff_images/' . $user->user_image;
            if (File::exists($path)) {

                File::delete($path);
            }
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Staff deleted successfully!'], 200);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //delete staff

    //update staff
    public function updateStaff(Request $request, $id)
    {

        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'No staff found!'], 404);
            }

            $validatedData = $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255',
                'user_phone' => 'required|regex:/^[0-9]+$/|max:20',
                'user_address' => 'required|string|max:400',
                'user_role' => 'required|string',
                'user_status'  => 'required|string',
                'user_priviledges' => 'nullable|array',
                'user_branch' => 'required|string',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                // 'company_id' => 'required',
                // Add more validation rules for other fields
            ]);
            if ($request->hasFile('upload_image')) {

                $path = 'public/staff_images/' . $user->user_image;
                // dd($path);
                if ($path) {
                    Storage::delete($path);
                }

                $image = $request->file('upload_image');
                $ext = $image->getClientOriginalExtension();
                $imageName = time() . "." . $ext;
                $image->storeAs('public/staff_images', $imageName);
                $user->user_image = 'storage/staff_images/' . $imageName;
            }


            // $fbAcc = $request->input('fb_acc');
            // $igAcc = $request->input('ig_acc');
            // $ttAcc = $request->input('tt_acc');

            // $socailLinks = "$fbAcc,$igAcc,$ttAcc";


            $user->name = $validatedData['user_name'];
            $user->email = $validatedData['user_email'];
            $user->phone = $validatedData['user_phone'];
            $user->address = $validatedData['user_address'];
            $user->user_role = $validatedData['user_role'];
            $user->user_status = $validatedData['user_status'];
            $user->user_priviledges = json_encode($validatedData['user_priviledges']);
            $user->user_branch = $validatedData['user_branch'];
            $user->company_id = $user->company_id;
            // $user->social_links = $socailLinks;
            $user->app_url = $this->appUrl;
            $user->update();

            return response()->json(['success' => true, 'message' => 'Staff updated successfully!', 'data' => ['updated_staff' => [
                "id"  => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "password" => $user->password,
                "company_id" => $user->company_id,
                "phone" => $user->phone,
                "address" => $user->address,
                "category" => $user->category,
                "user_image" => $user->user_image,
                "user_role" => $user->user_role,
                "user_status" => $user->user_status,
                "user_priviledges" => json_decode($user->user_priviledges),
                "user_branch" => $user->user_branch,
                "app_url" => $user->app_url,
                "country" => $user->country,
                "state" => $user->state,
                "city" => $user->city,
                "language" => $user->language,
                "zip_code" => $user->zip_code,
            ]]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //update staff

    //add staff
    public function addStaff(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255|unique:users,email',
                'user_phone' => 'required|regex:/^[0-9]+$/|max:20',
                'user_address' => 'required|string|max:400',
                'user_password' => 'nullable|string',
                'is_password' => 'required|string',
                'user_role' => 'required|string',
                'user_status'  => 'required|string',
                'user_priviledges' => 'nullable|array',
                'user_branch' => 'required|string',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);

            $password = ($validatedData['is_password'] == '1') ? $validatedData['user_password'] : rand();

            $user = Auth::user();

            $dataToInsert = [
                'name' => $validatedData['user_name'],
                'email' => $validatedData['user_email'],
                'phone' => $validatedData['user_phone'],
                'address' => $validatedData['user_address'],
                'user_role' => $validatedData['user_role'],
                'user_status' => $validatedData['user_status'],
                'user_priviledges' => json_encode($validatedData['user_priviledges']), // Assuming user_priviledges is a JSON field in the database
                'user_branch' => $validatedData['user_branch'],
                'company_id' => $user->company_id,
                'app_url' => $this->appUrl,
                'password' => md5($password),
            ];

            if (!empty($validatedData['upload_image'])) {
                // Handle image upload
                $image = $validatedData['upload_image'];
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/staff_images', $imageName);
                $dataToInsert['user_image'] = 'storage/staff_images/' . $imageName;
            } else {
                $dataToInsert['user_image'] = 'assets/images/user.png';
            }

            $lastInsertedId = DB::table('users')->insertGetId($dataToInsert);

            $emailData = [
                'email' => $validatedData['user_email'],
                'password' => $password,
            ];

            $mail = new StaffRegistrationMail($emailData);

            try {
                Mail::to($validatedData['user_email'])->send($mail);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            $addedStaff = DB::table('users')->where('id', $lastInsertedId)->first();

            return response()->json(['success' => true, 'message' => 'Staff added successfully!', 'data' => ['added_staff' => [
                "id"  => $addedStaff->id,
                "name" => $addedStaff->name,
                "email" => $addedStaff->email,
                "password" => $addedStaff->password,
                "company_id" => $addedStaff->company_id,
                "phone" => $addedStaff->phone,
                "address" => $addedStaff->address,
                "category" => $addedStaff->category,
                "user_image" => $addedStaff->user_image,
                "user_role" => $addedStaff->user_role,
                "user_status" => $addedStaff->user_status,
                "user_priviledges" => json_decode($addedStaff->user_priviledges),
                "user_branch" => $addedStaff->user_branch,
                "app_url" => $addedStaff->app_url,
                "country" => $addedStaff->country,
                "state" => $addedStaff->state,
                "city" => $addedStaff->city,
                "language" => $addedStaff->language,
                "zip_code" => $addedStaff->zip_code,
            ]]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //add staff

    //----------------------------------------------------Staff APIs------------------------------------------------------//


    //getting dashboard
    public function adminDashboard()
    {
        try {

            $user = Auth::user();

            $data = Customers::where('company_id', $user->company_id)->get();

            $totalCustomers = Customers::where('company_id', $user->company_id)->count();

            if ($data->count() > 0) {
                return response()->json(['success' => true, 'data' => ['cutomers' => $data, 'totalCustomers' => $totalCustomers]], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No records found'], 404);
            }
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //getting dashboard

    //----------------------------------------------------Authentication APIs------------------------------------------------------//

    //login
    public function login(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $userRoles = ['admin', 'manager', 'cashier', 'chef', 'waiter', 'rider'];
            $user = User::where('email', $email)->first();

            if (!$user || md5($password) !== $user->password) {
                return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            }

            $userRole = $user->user_role;
            if (!in_array($userRole, $userRoles)) {
                // User role is not allowed to login
                return response()->json(['message' => 'User role not allowed to login'], 401);
            }

            // Generate a personal access token for the user
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['success' => true, 'message' => 'Login successful!', 'access_token' => $token, 'user_details' => [
                "id"  => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "password" => $user->password,
                "company_id" => $user->company_id,
                "phone" => $user->phone,
                "address" => $user->address,
                "category" => $user->category,
                "user_image" => $user->user_image,
                "user_role" => $user->user_role,
                "user_status" => $user->user_status,
                "user_priviledges" => json_decode($user->user_priviledges),
                "user_branch" => $user->user_branch,
                "app_url" => $user->app_url,
                "country" => $user->country,
                "state" => $user->state,
                "city" => $user->city,
                "language" => $user->language,
                "zip_code" => $user->zip_code,
            ]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //login
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            // Revoke the user's token(s)
            $user->tokens()->delete();

            return response()->json(['success' => true, 'message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    //request for a service
    public function makeRequest(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'req_name' => 'required|string|max:255',
                'req_company_name' => 'required|string|max:255',
                'req_email' => 'required|email|max:255|unique:users,email|unique:service_requests,req_email',
                'req_address' => 'required|string|max:255',
                'req_number' => 'required|string',
            ]);

            // Create a new ServiceRequests instance and fill it with the validated data
            $serviceRequest = new ServiceRequests([
                'req_name' => $validatedData['req_name'],
                'req_company_name' => $validatedData['req_company_name'],
                'req_email' => $validatedData['req_email'],
                'req_address' => $validatedData['req_address'],
                'req_number' => $validatedData['req_number'],
            ]);

            // Save the record to the database
            $serviceRequest->save();

            // Optionally, you can return a response or redirect to a success page
            return response()->json(['success' => true, 'message' => 'Service request added successfully! You will be notified through E-mail.'], 200);
        } catch (\Exception $e) {
            // Handle other exceptions, such as database messages
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //request for a service

    //forgot password
    public function forgotPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string',
            ]);
            $email = $validatedData['email'];

            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'The user does not exist.'], 400);
            }

            $otp = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $emailData = [
                'name' => $user->name,
                'otp' => $otp,
            ];

            $mail = new forgotPasswordMail($emailData);
            try {
                Mail::to($email)->send($mail);
                $user->otp = $otp;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            $user->save();

            return response()->json(['success' => true, 'message' => 'Please check your mail for the otp.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //forgot password

    //validate otp
    public function validateOtp(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'otp' => 'required|integer',
            ]);
            $otp = $validatedData['otp'];

            $otpCheck = User::where('otp', $otp)->first();

            if (!$otpCheck) {
                return response()->json(['success' => false, 'message' => 'Provided otp is incorrect!'], 400);
            }

            return response()->json(['success' => true, 'message' => 'Otp is correct! Now you can reset your password.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //validate otp

    //reset password
    public function resetPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'otp' => 'required|integer',
                'new_password' => 'required'
            ]);

            $otp = $validatedData['otp'];
            $password = md5($validatedData['new_password']);

            $user = User::where('otp', $otp)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User did not found against the provided otp!'], 404);
            }

            $user->password = $password;

            $user->save();
            $user->update(['otp' => null]);

            return response()->json(['success' => true, 'message' => 'Your password is successfully changed. Now! you can login with your new password'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //reset password

    //----------------------------------------------------Authentication APIs------------------------------------------------------//

    //----------------------------------------------------User APIs------------------------------------------------------//
    //get user detail
    public function getUserDetails(Request $request)
    {
        $user = $request->user(); // This will give you the authenticated user

        $company = Company::find($user->company_id);
        $user->company_name = $company ? $company->company_name : 'Unknown company';
        $user->company_image = $company ? $company->company_image : 'No Image';
        return response()->json(['success' => true, 'data' => ['user_details' => [
            "id"  => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "password" => $user->password,
            "company_id" => $user->company_id,
            "phone" => $user->phone,
            "address" => $user->address,
            "category" => $user->category,
            "user_image" => $user->user_image,
            "user_role" => $user->user_role,
            "user_status" => $user->user_status,
            "user_priviledges" => json_decode($user->user_priviledges),
            "user_branch" => $user->user_branch,
            "app_url" => $user->app_url,
            "country" => $user->country,
            "state" => $user->state,
            "city" => $user->city,
            "language" => $user->language,
            "zip_code" => $user->zip_code,
        ]]], 200);
    }
    //get user detail

    //get company  details
    public function getCompanyDetails(Request $request)
    {
        $user = Auth::user();
        try {
            $company = Company::find($user->company_id);

            $companyDetails = [
                'company_id' => $company->company_id,
                'companyName' => $company->company_name,
                'email' => $company->company_email,
                'phone' => $company->company_phone,
                'address' => $company->company_address,
                'saleTax' => $company->sale_tax,
                'inventory' => $company->inventory,
                'currency' => $company->currency,
                'kitchenSlip' => $company->kitchen_slip,
                'serviceCharges' => $company->service_charges,
                'app_url' => $company->app_url,
                'company_image' => $company->company_image,
                'uiLayout' => $company->ui_layout,
                'printBillBorder' => $company->print_bill_border,
            ];

            return response()->json(['success' => true, 'data' => ['company_details' => $companyDetails]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //get company  details

    //update company details
    public function updateCompanyDetails(Request $request)
    {
        $user = Auth::user();
        try {

            $validatedData  = $request->validate([
                'companyName' => 'nullable|string',
                'email' => 'nullable|string',
                'phone' => 'nullable|regex:/^[0-9]+$/|max:20',
                'saleTax' => 'nullable|numeric',
                'inventory' => 'nullable|string',
                'currency' => 'nullable|string',
                'kitchenSlip' => 'nullable|string',
                'address' => 'nullable|string',
                'serviceCharges'  => 'nullable|numeric',
                'uiLayout' => 'nullable',
                'printBillBorder' => 'nullable',
            ]);

            $company = Company::where('company_id', $user->company_id)->first();

            if (!$company) {
                return response()->json(['success' => false, 'message' => 'company not found'], 404);
            }

            $company->company_name = $validatedData['companyName'];
            $company->company_email = $validatedData['email'];
            $company->company_phone = $validatedData['phone'];
            $company->sale_tax = $validatedData['saleTax'];
            $company->inventory = $validatedData['inventory'];
            $company->currency = $validatedData['currency'];
            $company->kitchen_slip = $validatedData['kitchenSlip'];
            $company->company_address = $validatedData['address'];
            $company->service_charges = $validatedData['serviceCharges'];
            $company->ui_layout = $validatedData['uiLayout'];
            $company->print_bill_border = $validatedData['printBillBorder'];

            $company->save();

            return response()->json(['success' => true, 'message' => 'company profile updated successfully!', 'data' => ['updated_company' => [
                'companny_id' => $company->company_id,
                'companyName' => $company->company_name,
                'email' => $company->company_email,
                'phone' => $company->company_phone,
                'saleTax' => $company->sale_tax,
                'inventory' => $company->inventory,
                'currency' => $company->currency,
                'kitchenSlip' => $company->kitchen_slip,
                'address' => $company->company_address,
                'serviceCharges' => $company->service_charges,
                'uiLayout' => $company->ui_layout,
                'printBillBorder' => $company->print_bill_border,
            ]]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //update company details

    //update user detail
    public function updateUserDetail(Request $request)
    {
        try {
            $user = Auth::user(); // Get the authenticated user

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $validatedData = $request->validate([
                'fullName' => 'nullable|string|max:255',
                'country' => 'nullable|string',
                'state' => 'nullable|string',
                'city' => 'nullable|string',
                'phone' => 'nullable|regex:/^[0-9]+$/|max:20',
                'zip' => 'nullable|string',
                'language' => 'nullable|string',
                'password' => 'nullable',
                'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                // 'address' => 'nullable|string|max:400',
            ]);

            // Check the user_role and determine the appropriate folder for image storage
            if ($user->user_role == 'admin') {
                // For user_role 1, update the company image
                $folder = 'company_images';
                // You might want to fetch the company details here and update the image accordingly
                $company = Company::find($user->company_id);
                if ($company) {
                    // Update the company image using the company details
                    if ($request->hasFile('upload_image')) {
                        if ($company->company_image) {
                            // Remove the 'public/' prefix to store only the relative path
                            Storage::delete($company->company_image);
                        }
                        $imagePath = $request->file('upload_image')->store("public/$folder");
                        // Update the company_image field with the complete path
                        $company->company_image = str_replace('public/', 'storage/', $imagePath);

                        $user->user_image = str_replace('public/', 'storage/', $imagePath);

                        $company->app_url = $this->appUrl;
                        // Save the updated company data to the database
                        $company->save();
                        $user->save();
                    }
                }
            } else {
                // For other user roles, update the staff image as before
                $folder = 'staff_images';
                if ($request->hasFile('upload_image')) {
                    if ($user->user_image) {
                        // Remove the 'public/' prefix to store only the relative path
                        Storage::delete($user->user_image);
                    }
                    $imagePath = $request->file('upload_image')->store("public/$folder");
                    // Update the user_image field with the complete path
                    $user->user_image = str_replace('public/', 'storage/', $imagePath);
                }
            }

            // Conditionally update user attributes if they are not null
            if ($validatedData['fullName'] !== null) {
                $user->name = $validatedData['fullName'];
            }

            if ($validatedData['phone'] !== null) {
                $user->phone = $validatedData['phone'];
            }

            if ($validatedData['password'] !== null) {
                $user->password = md5($validatedData['password']);
            }
            $user->country = $validatedData['country'];
            $user->state = $validatedData['state'];
            $user->city = $validatedData['city'];
            $user->language = $validatedData['language'];
            $user->zip_code = $validatedData['zip'];
            // if ($validatedData['address'] !== null) {
            //     $user->address = $validatedData['address'];
            // }
            // Save the updated user data to the database
            $user->app_url = $this->appUrl;

            $user->save();

            return response()->json(['success' => true, 'message' => 'Data updated successfully', 'data' => ['updated_profile' => [
                'id'  => $user->id,
                'fullName' => $user->name,
                'country' => $user->country,
                'state' => $user->state,
                'city' => $user->city,
                'phone' => $user->phone,
                'zip' => $user->zip_code,
                'language' => $user->language,
                'password' => $user->password,
                'branch' => $user->user_branch,
                'user_image' => $user->user_image,
                'app_url' => $user->app_url
            ]]], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    //update user detail
    //----------------------------------------------------User APIs------------------------------------------------------//

}
