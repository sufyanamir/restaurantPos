<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{

    public function index(){
        $customersCount=Customers::count();
        $activeCount = Customers::where('customer_status',1)->count();
        $pendingCount = Customers::where('customer_status',0)->count();


        
        // dd($customers);
        $user_details = session('user_details');

        $userId = $user_details['user_id'];

        $customers = Customers::where('added_user_id', $userId)->get();

        return view('customers', ['customers'=>$customers,
        'userDetails'=>$user_details,
        'customersCount'=>$customersCount,
        'activeCount'=>$activeCount,
        'pendingCount'=>$pendingCount,

    ]);
    }
    

    public function addCustomer(Request $request){

        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,customer_email',
            'phone' => 'required|regex:/^[0-9]+$/|max:20',
            'address' => 'required|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'company_id' => 'required',
            'added_user_id' => 'required',
            // Add more validation rules for other fields
        ]);
        $fbAcc = $request->input('fb_acc');
        $igAcc = $request->input('ig_acc');
        $ttAcc = $request->input('tt_acc');

        $status = 0;

        $socailLinks="$fbAcc,$igAcc,$ttAcc";

        $dataToInsert = [
            'customer_name' => $validatedData['name'],
            'customer_email' => $validatedData['email'],
            'customer_phone' => $validatedData['phone'],
            'customer_address' => $validatedData['address'],
            'company_id' => $validatedData['company_id'],
            'added_user_id' => $validatedData['added_user_id'],
            'customer_social_links' => $socailLinks,
            'customer_status' => $status,
            'app_url' => 'https://scubadiving.thewebconcept.tech/',
            // Add other fields as needed
        ];

        if (!empty($validatedData['upload_image'])) {
            $dataToInsert['customer_image'] = $validatedData['upload_image'];
        }

        DB::table('customers')->insert($dataToInsert);

        if ($request->hasFile('upload_image')) {
            // Get the uploaded file
            $image = $request->file('upload_image');
            
            // Generate a unique name for the image
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Store the image in the specified storage location
            $image->storeAs('public/customer_images', $imageName); // Adjust storage path as needed
            
            // Now, if you want to associate the uploaded image filename with the inserted record, you would need to retrieve the last inserted ID.
            $lastInsertedId = DB::getPdo()->lastInsertId();
    
            // Update the 'upload_image' field for the inserted record
            DB::table('customers')
                ->where('customer_id', $lastInsertedId)
                ->update(['customer_image' => 'storage/customer_images/' . $imageName]);
        }else {
            $lastInsertedId = DB::getPdo()->lastInsertId();
    
            // Update the 'upload_image' field for the inserted record
            DB::table('customers')
                ->where('customer_id', $lastInsertedId)
                ->update(['customer_image' => 'assets/images/user.png']);
        }
        
        // Optionally, you can redirect back with a success message
        return redirect()->back()->with('status', 'Customer added successfully.');

    }


    public function update(Request $request ,$id){
        $user = Customers::find($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9]+$/|max:20',
            'address' => 'required|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            // Add more validation rules for other fields
        ]);
        if($request->hasFile('upload_image')){
            
                $path = 'public/customer_images/'.$user->customer_image;
                // dd($path);
                if ($path) 
                {
                    Storage::delete($path);
                }
                
                $image = $request->file('upload_image');
                $ext = $image->getClientOriginalExtension();
                $imageName = time().".".$ext;
                $image->storeAs('public/customer_images', $imageName);
                $user->customer_image = 'storage/customer_images' . $imageName; 


        }   
        

        $fbAcc = $request->input('fb_acc');
        $igAcc = $request->input('ig_acc');
        $ttAcc = $request->input('tt_acc');

        $socailLinks="$fbAcc,$igAcc,$ttAcc";


        $user->customer_name = $validatedData['name'];
        $user->customer_email = $validatedData['email'];
        $user->customer_phone = $validatedData['phone'];
        $user->customer_address = $validatedData['address'];
        $user->customer_social_links = $socailLinks;
        $user->update();

        return redirect('customers')->with('status','Customer Updated Successfully');




    }

    public function destroy($id)
    {
        $user = Customers::find($id);
        $path = 'storage/customer_images/'.$user->customer_image;
        if (File::exists($path)) {
            
            File::delete($path);
        }
        $user->delete();
        return redirect('customers')->with('status','Customer Deleted successfully');


    }
}
