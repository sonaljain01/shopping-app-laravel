<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\PinCode;
class CityController extends Controller
{
    public function index(Request $request)
    {
        $states = State::all();  // Get all states
        $cities = City::with('state')->orderBy('name');  // Get cities with state information and paginate get();  // Get cities with state information
        
        // if ($request->get('keyword') != "") {
        //     $cities = $cities->where('cities.name', 'like', '%' . $request->keyword . '%');
            

        // }
        if ($request->get('keyword') != "") {
            $keyword = $request->keyword;
        
            // Assuming $cities is already an Eloquent query builder instance
            $cities = $cities->where(function ($query) use ($keyword) {
                $query->where('cities.name', 'like', '%' . $keyword . '%')
                      ->orWhereHas('state', function ($stateQuery) use ($keyword) {
                          $stateQuery->where('name', 'like', '%' . $keyword . '%');
                      });
            });
        }
        $cities = $cities->paginate(10);
        return view('admin.city.index', compact('states', 'cities'));
    }

    public function create()
    {
        // arrange state in ascending order
        $states = State::orderBy('name')->get();  // Get active states
        $pincodes = Pincode::all();

        return view('admin.city.create', compact('states', 'pincodes'));
    }
    public function storeCity(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            // 'pincode' => 'required|string|max:6|unique:pin_codes,pincode',
            // 'is_enabled' => 'required|boolean',
        ]);
        // $existingPincode = Pincode::where('pincode', $validated['pincode'])->first();

        // if ($existingPincode) {
        //     return redirect()->back()->withErrors(['pincode' => 'This pincode already exists.'])->withInput();
        // }

        // Check if the city with the same name and state already exists
        $cityExists = City::where('name', $validated['name'])->where('state_id', $validated['state_id'])->first();

        if ($cityExists) {
            return redirect()->back()->withErrors(['name' => 'This city already exists in the selected state.'])->withInput();
        }

        $city = new City();
        $city->name = $validated['name'];
        $city->state_id = $validated['state_id'];
        $city->save();

        return redirect()->route('city.index')->with('success', 'City created successfully.');
    }

    public function edit($id)
    {
        $city = City::with('pincode')->findOrFail($id);  // Retrieve city with related pincode data
        $states = State::all();  // Get all states for dropdown selection

        return view('admin.city.edit', compact('city', 'states'));
    }

    public function update(Request $request, $cityId)
    {
        // Fetch the existing city
        $city = City::findOrFail($cityId);

        // Validate incoming request data
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            // 'is_enabled' => 'required|boolean',
        ]);

        // Check if a city with the same name and state exists (excluding the current city)
        $cityExists = City::where('name', $validated['name'])
            ->where('state_id', $validated['state_id'])
            ->where('id', '!=', $city->id)
            ->first();

        if ($cityExists) {
            return redirect()->back()->withErrors(['error' => 'This city already exists in the selected state.'])->withInput();
        }

        // Update the city details
        $city->name = $validated['name'];
        $city->state_id = $validated['state_id'];
        // $city->is_enabled = $validated['is_enabled'];
        $city->save();

      

        return redirect()->route('city.index')->with('success', 'City updated successfully!');
    }
    // Handle toggling the city status (enable/disable)
    public function toggleCityDelivery($cityId)
    {
        $city = City::findOrFail($cityId);
        $city->is_enabled = !$city->is_enabled;
        $city->save();
        $statusMessage = $city->is_enabled ? 'Delivery Enabled' : 'Delivery Disabled';
        return redirect()->route('city.index')->with('success', 'City delivery status updated to: ' . $statusMessage . '.');
    }

    public function destroy($cityId, Request $request)
    {
        $city = City::findOrFail($cityId);
        $city->delete();
        
        return redirect()->route('city.index')->with('success', 'City deleted successfully!');

    }
}
