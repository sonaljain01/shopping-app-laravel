<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
class StateController extends Controller
{
    public function index(Request $request)
    {
        $query = State::query();
        $states = State::all();
        if ($request->get('keyword') != "") {
            $query->where('states.name', 'like', '%' . $request->keyword . '%');


        }
        $states = $query->paginate(10);

        return view('admin.state.index', compact('states'));
    }

    public function create()
    {
        // arrange state in ascending order
        $states = State::orderBy('name')->get();  // Get active states


        return view('admin.state.create', compact('states'));
    }
    public function storeState(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',


        ]);




        // Check if the city with the same name and state already exists
        $stateExists = State::where('name', $validated['name'])->first();

        if ($stateExists) {
            return redirect()->back()->withErrors(['error' => 'This state already exists.'])->withInput();
        }

        $state = new State();
        $state->name = $validated['name'];

        $state->save();

        // Create the new pincode if it doesn't exist

        return redirect()->route('state.index')->with('success', 'State created successfully.');
    }

    public function toggleStatus($id)
    {
        $state = State::findOrFail($id);
        $state->is_enabled = !$state->is_enabled;
        $state->save();

        // Update the status of all cities within the state
        // $state->cities()->update(['is_enabled' => $state->is_enabled]);
        return redirect()->route('state.index')->with('success', 'State status updated successfully.');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id);  // Retrieve city with related pincode data
        

        return view('admin.state.edit', compact('state'));
    }

    public function update(Request $request, $stateId)
    {
        // Fetch the existing city
        $state = State::findOrFail($stateId);

        // Validate incoming request data
        $validated = $request->validate([
            
            'name' => 'required|string|max:255',
           
        ]);

        // Check if a city with the same name and state exists (excluding the current city)
        $stateExists = State::where('name', $validated['name'])->first();

        if ($stateExists) {
            return redirect()->route('state.index')->with(['error' => 'This state already exists in the selected state.'])->withInput();
        }

        // Update the city details
        $state->name = $validated['name'];
       
        $state->save();

      

        return redirect()->route('state.index')->with('success', 'State updated successfully!');
    }

    public function destroy($stateId, Request $request)
    {
        $state = State::findOrFail($stateId);
        $state->delete();
        // $request->session()->flash('success', 'City deleted successfully');
        return redirect()->route('state.index')->with('success', 'State deleted successfully!');

    }
}
