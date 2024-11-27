<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\PickupAddressRequest;

class PickupController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $keyword = $request->get('keyword');
        $pickupAddresses = PickupAddress::query()
            ->where('user_id', auth()->id())
            ->when($keyword, fn($query) => $query->where('name', 'like', "%$keyword%"))
            ->paginate(10);

        return view('admin.pickup.index', compact('pickupAddresses'));
    }

    public function create()
    {
        return view('admin.pickup.create');
    }

    public function store(PickupAddressRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create pickup address in the database
            $pickupAddress = PickupAddress::create(array_merge(
                $request->validated(),
                ['user_id' => auth()->id()]
            ));

            // Send request to Shiprocket API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('SHIPROCKET_TOKEN'),
            ])->post('https://apiv2.shiprocket.in/v1/external/settings/company/addpickup', [
                'pickup_location' => $pickupAddress->tags,
                'name' => $pickupAddress->name,
                'address_1' => $pickupAddress->address_1,
                'address_2' => $pickupAddress->address_2,
                'phone' => $pickupAddress->phone,
                'city' => $pickupAddress->city,
                'state' => $pickupAddress->state,
                'zip' => $pickupAddress->zip,
                'country' => $pickupAddress->country,
            ]);

            if (!$response->successful()) {
                DB::rollBack();
                $error = $response->json()['errors']['address'][0] ?? 'An error occurred';
                return back()->with('error', $error);
            }

            DB::commit();
            return redirect()->route('pickup.index')->with('success', 'Pickup address created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pickup.index')->with('error', 'Pickup address could not be created: ' . $e->getMessage());
        }
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
            'tags' => 'nullable|string|max:255|unique:pickup_addresses,tags,' . $id,
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            PickupAddress::query()->where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $pickup->update($validated);

        return redirect()->route('pickup.index')->with('success', 'Pickup address updated successfully.');
    }

    public function destroy($id)
    {
        $pickup = PickupAddress::findOrFail($id);
        $pickup->delete();

        return redirect()->route('pickup.index')->with('success', 'Pickup address deleted successfully.');
    }
}
