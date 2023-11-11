<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;
use App\Models\ServiceOverheads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServicesController extends Controller
{
    public function destroy($id)
    {
        $service = Services::find($id);

        if (!$service) {
            return redirect('services')->with('error', 'Service not found');
        }

        // Delete associated overheads.
        $service->overheads()->delete();

        $path = 'storage/service_images/' . $service->service_image;

        if (File::exists($path)) {
            File::delete($path);
        }

        $service->delete();

        return redirect('services')->with('status', 'Service deleted successfully');
    }

    public function index()
    {
        // Retrieve user details from the session
        $userDetails = session('user_details');
        $userId = $userDetails['user_id'];

        // Retrieve services that have the same added_user_id as the user's id
        $services = Services::where('added_user_id', $userId)
            ->with('serviceOverheads')
            ->get();

        // Retrieve all data from the services_overheads table
        $allServiceOverheads = ServiceOverheads::all();

        // Calculate the sum of overhead_cost for each service using DB::raw
        $totalOverheadCosts = ServiceOverheads::select('service_id', DB::raw('SUM(overhead_cost) as total_cost'))
            ->groupBy('service_id')
            ->get()
            ->keyBy('service_id');

        // dd($allServiceOverheads);
        return view('services', [
            'services' => $services,
            'totalOverheadCosts' => $totalOverheadCosts, // Pass the calculated sums to the view
            'userDetails' => $userDetails,
            'allServiceOverheads' => $allServiceOverheads, // Pass all service overhead data to the view
        ]);
    }



    public function addService(Request $request)
    {
        // dd($request);
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'charges' => 'required|numeric',
            'description' => 'required|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'added_user_id' => 'required',
            'company_id' => 'required',
            'cost_name' => 'array',
            'cost' => 'array',
            'service_duration' => 'required|numeric',
            // 'overheads' => 'array', // Define 'overheads' as an array
        ]);

        // Insert data into the 'services' table
        $service = new Services([
            'service_name' => $validatedData['name'],
            'service_subtitle' => $validatedData['subtitle'],
            'service_charges' => $validatedData['charges'],
            'service_desc' => $validatedData['description'],
            'added_user_id' => $validatedData['added_user_id'],
            'company_id' => $validatedData['company_id'],
            'service_duration' => $validatedData['service_duration'],
            'app_url' => 'https://scubadiving.thewebconcept.tech/',
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
        if (!empty($request->input('cost_name'))) {
            $overheads = $request->input('cost_name');
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
        return redirect()->back()->with('success', 'Service added successfully.');
    }


    public function update(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'charges' => 'required|numeric',
            'description' => 'required|string|max:500',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'added_user_id' => 'required',
            'company_id' => 'required',
            'service_duration' => 'required|numeric',
            'overheads' => 'array', // Define 'overheads' as an array
            // 'overheads.*.cost_name' => 'required|string|max:255',
            // 'overheads.*.cost' => 'required|numeric',
        ]);

        // Find the service to be updated
        $service = Services::find($id);

        if (!$service) {
            return redirect()->back()->with('error', 'Service not found.');
        }

        // Update the service data
        $service->service_name = $validatedData['name'];
        $service->service_subtitle = $validatedData['subtitle'];
        $service->service_charges = $validatedData['charges'];
        $service->service_desc = $validatedData['description'];
        $service->added_user_id = $validatedData['added_user_id'];
        $service->company_id = $validatedData['company_id'];
        $service->service_duration = $validatedData['service_duration'];

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
        return redirect()->back()->with('success', 'Service updated successfully.');
    }
}
