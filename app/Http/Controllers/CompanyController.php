<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{

    public function index()
    {

        $user_details = session('user_details');

        $company = DB::table('users')
            ->join('company', 'users.company_id', '=', 'company.company_id')
            ->where('users.user_role', 1)
            ->select('users.*', 'company.*')
            ->get();
        return view('company', [
            'company' => $company,
            'user_details' => $user_details,
        ]);
    }

    public function addCompany(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:company,company_email',
            'phone' => 'required|regex:/^[0-9]+$/|max:20',
            'address' => 'required|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_phone' => 'required|regex:/^[0-9]+$/|max:20',
            'admin_address' => 'required|string|max:500',
            'password' => 'required|string|max:500',
            // Add more validation rules for other fields
        ]);

        try {
            // Prepare data for company insertion
            $companyData = [
                'company_name' => $validatedData['name'],
                'company_email' => $validatedData['email'],
                'company_phone' => $validatedData['phone'],
                'company_address' => $validatedData['address'],
                'app_url' => 'https://scubadiving.thewebconcept.tech/',
            ];

            // Upload and store the company image if it exists
            if ($request->hasFile('upload_image')) {
                $image = $request->file('upload_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/company_images', $imageName); // Adjust storage path as needed
                $companyData['company_image'] = 'storage/company_images/' . $imageName;
            }

            // Insert data into the 'company' table using DB facade
            $companyID = DB::table('company')->insertGetId($companyData);

            // Insert data into the 'users' table using DB facade
            DB::table('users')->insert([
                'name' => $validatedData['admin_name'],
                'email' => $validatedData['admin_email'],
                'phone' => $validatedData['admin_phone'],
                'address' => $validatedData['admin_address'],
                'password' => md5($validatedData['password']),
                'company_id' => $companyID,
                'user_role' => '1',
                'app_url' => 'https://scubadiving.thewebconcept.tech/',
                // Add other fields as needed
            ]);

            // Prepare a success response
            $response = [
                'success' => true,
                'message' => 'Company added successfully.',
                'company_id' => $companyID,
            ];

            return redirect()->back()->with($response);
        } catch (\Exception $e) {
            // Handle any unexpected exceptions here
            $errorResponse = [
                'success' => false,
                'message' => 'An error occurred while adding the company.',
                'error' => $e->getMessage(), // Optionally include the error message
            ];

            return response()->json($errorResponse, 500); // Return a 500 Internal Server Error response
        }
    }


    public function destroy($id)
    {
        $company = Company::find($id);
        $path = 'storage/company_images/' . $company->company_image;

        if (File::exists($path)) {
            File::delete($path);
        }

        $company->delete();

        return redirect('company')->with('status', 'Company Deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id);




        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9]+$/|max:20',
            'address' => 'required|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_phone' => 'required|regex:/^[0-9]+$/|max:20',
            'admin_address' => 'required|string|max:500',
            // Add more validation rules for other fields
        ]);

        if ($request->hasFile('upload_image')) {

            $path = 'public/company_images/' . $company->company_image;
            // dd($path);
            if ($path) {
                Storage::delete($path);
            }

            $image = $request->file('upload_image');
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;
            $image->storeAs('public/company_images', $imageName);
            $company->company_image = 'storage/company_images/' . $imageName;
        }

        $company->company_name = $validatedData['name'];
        $company->company_email = $validatedData['email'];
        $company->company_phone = $validatedData['phone'];
        $company->company_address = $validatedData['address'];

        $company->update();


        $user = $company->users()->where('user_role', 1)->first();


        if (!$user) {
            return redirect()->back()->with('status', 'Admin Not Exists.');
        }
        $user->name = $validatedData['admin_name'];
        $user->email = $validatedData['admin_email'];
        $user->phone = $validatedData['admin_phone'];
        $user->address = $validatedData['admin_address'];
        $user->update();
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
        return redirect()->back()->with('status', 'Company data updated Successfully.');
    }
}
