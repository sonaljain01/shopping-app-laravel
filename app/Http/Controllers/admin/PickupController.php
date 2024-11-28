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
    // public function index(Request $request)
    // {
    //     if (!auth()->check()) {
    //         return redirect()->route('login');
    //     }

    //     $keyword = $request->get('keyword');
    //     $pickupAddresses = PickupAddress::query()
    //         ->where('user_id', auth()->id())
    //         ->when($keyword, fn($query) => $query->where('name', 'like', "%$keyword%"))
    //         ->paginate(10);

    //     return view('admin.pickup.index', compact('pickupAddresses'));
    // }
    public function index(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('admin.login');
        }

        $addresses = PickupAddress::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);


        return view('admin.pickup.index', ['addresses' => $addresses]);
    }


    public function create()
    {
        return view('admin.pickup.create');
    }

    public function store(PickupAddressRequest $request)
    {
        try {
            $data = [
                'user_id' => auth()->user()->id,
                'name' => $request->name,
                'tag' => $request->tag,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'country' => $request->country,
                'is_default' => $request->is_default,
            ];

            DB::beginTransaction();
            PickupAddress::create($data);
            DB::commit();

            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('SHIPROCKET_TOKEN'),
            ])->post('https://apiv2.shiprocket.in/v1/external/settings/company/addpickup', [
                        'pickup_location' => $request->tag,
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                        'country' => $request->country,
                        'pin_code' => $request->pincode,
                    ]);
            if (!$res->successful()) {
                $error = $res->json()['errors']['address'][0];
                return back()->with('error', $error);
            }
            return back()->with('success', 'Pickup address created');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
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
            'email' => 'required|string|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:6',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'tag' => 'nullable|string|max:255|unique:pickup_addresses,tags,' . $id,
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            PickupAddress::query()->where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $pickup->update($validated);

        return redirect()->route('pickup.index')->with('success', 'Pickup address updated successfully.');
    }

    // public function destroy($id)
    // {
    //     $pickup = PickupAddress::findOrFail($id);
    //     $pickup->delete();

    //     return redirect()->route('pickup.index')->with('success', 'Pickup address deleted successfully.');
    // }
}
