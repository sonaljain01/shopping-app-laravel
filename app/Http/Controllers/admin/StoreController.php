<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingRequest;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use App\Models\StoreSetting;
class StoreController extends Controller
{
    public function index(Request $request)
    {
        $stores = StoreSetting::all();
        return view('admin.store.index', compact('stores'));
    }

    public function create(Request $request)
    {
        return view('admin.store.create');
    }

    public function store(StoreSettingRequest $request)
    {
        $validatedData = $request->validated();

        $store = StoreSetting::create($validatedData);

        return redirect()->route('stores.index')->with('success', 'Store created successfully.');
    }

    public function destroy($id, Request $request)
    {
        $store = StoreSetting::findOrFail($id);
        $store->delete();
        $request->session()->flash('success', 'Store deleted successfully!');
        return response()->json([
            'status' => true,
            'message' => 'Store deleted successfully'
        ]);
    }

    public function edit($id)
    {
        $store = StoreSetting::find($id);
        return view('admin.store.edit', compact('store'));
    }

    public function update(StoreSettingRequest $request, $id)
    {
        $validatedData = $request->validated();

        $store = StoreSetting::findOrFail($id);
        $store->update($validatedData);

        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }
}
