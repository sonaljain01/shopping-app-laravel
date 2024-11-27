<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PickupAddress;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $pickupAddresses = PickupAddress::query()
            ->when($keyword, fn($query) => $query->where('name', 'like', "%$keyword%"))
            ->paginate(10);
        return view('admin.pickup.index', compact('pickupAddresses'));
    }

    public function create()
    {
        return view('admin.pickup.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'phone' => 'required|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|numeric',
            'country' => 'required|string',
            'is_default' => 'nullable|boolean',
            'tags' => 'nullable|string',
        ]);

        PickupAddress::create($validated);

        return redirect()->route('pickup.index')->with('success', 'Pickup address created successfully.');
    }

    public function edit($id)
    {
        $pickup = PickupAddress::findOrFail($id); 
        return view('admin.pickup.edit', compact('pickup'));
    }

    public function update(Request $request, $id)
    {
        $pickup = PickupAddress::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'tags' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);
       
        if ($request->boolean('is_default')) {
            PickupAddress::query()->update(['is_default' => false]);
        }

        $pickup->update([
            'name' => $validated['name'],
            'address_1' => $validated['address_1'],
            'address_2' => $validated['address_2'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'country' => $validated['country'],
            'phone' => $validated['phone'],
            'tags' => $validated['tags'],
            'is_default' => $validated['is_default'],
        ]);

        return redirect()->route('pickup.index')->with('success', 'Pickup Address updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $pickup = PickupAddress::findOrFail($id);
        $pickup->delete();
        $request->session()->flash('success', 'Pickup Address deleted successfully!');
        return redirect()->route('pickup.index')->with('success', 'Pickup Address deleted successfully.');
    }
}
