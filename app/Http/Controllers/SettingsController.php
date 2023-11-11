<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Compnay;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{

    public function index()
    {
        $userDetails = Session('user_details');
        $companyId = $userDetails['company_id'];


        $company = Company::where('company_id', $companyId)->first();

        if ($userDetails && isset($userDetails['user_id'])) {
            // Retrieve the 'role' from user details
            $userId = $userDetails['user_id'];
            $User = User::where('id', $userId)->first();
            return view(
                'settings',
                [
                    'userDetails' => $User,
                    'company' => $company
                ]
            );
            // Now $userRole contains the role of the currently logged-in user
            // You can use $userRole as needed in your controller method
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        // Validate the form data
        $validatedData = $request->validate([
            'user_name' => 'nullable|string|max:255',
            'user_phone' => 'nullable|regex:/^[0-9]+$/|max:20',
            'user_address' => 'nullable|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'company_name' => 'nullable|string|max:255',
            'company_phone' => 'nullable|regex:/^[0-9]+$/|max:20',
            'company_address' => 'nullable|string|max:500',
            'user_password' => 'nullable|string|max:500',

            // Add more validation rules for other fields
        ]);


        $user->name = $validatedData['user_name'];
        $user->phone = $validatedData['user_phone'];
        $user->address = $validatedData['user_address'];
        if ($request->user_password) {
            $user->password = md5($validatedData['user_password']);
        }
        $user->update();


        $company = Company::where('company_id', $request->company_id)->first();


        if (!$company) {
            return redirect()->back()->with('status', 'Settings updated Successfully.');
        }
        $company->company_name = $validatedData['company_name'];
        $company->company_phone = $validatedData['company_phone'];
        $company->company_address = $validatedData['company_address'];
        if ($request->hasFile('upload_image')) {
            // Get the current user's profile image path
            $currentImage = $company->company_image;

            // Check if the current image exists and delete it
            if ($currentImage && Storage::exists("public/company_images/$currentImage")) {
                Storage::delete("public/company_images/$currentImage");
            }

            // Upload the new image
            $image = $request->file('upload_image');
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;
            $image->storeAs('public/company_images', $imageName);

            // Update the user's profile image
            $company->company_image = 'storage/company_images/' . $imageName;
        }
        $company->update();
        // DB::table('company')->update([
        //     'company_name' => $validatedData['name'],
        //     'company_email' => $validatedData['email'],
        //     'company_phone' => $validatedData['phone'],
        //     'company_address' => $validatedData['address'],
        // ]);

        // DB::table('users')->update([
        //     'name' => $validatedData['admin_name'],
        //     'email' => $validatedData['admin_email'],
        //     'phone' => $validatedData['admin_phone'],
        //     'address' => $validatedData['admin_address'],
        // ]);
        return redirect()->back()->with('status', 'Settings updated Successfully.');
    }
}
