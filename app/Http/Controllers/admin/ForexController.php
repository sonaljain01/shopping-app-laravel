<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ForexRate;
use DB;
class ForexController extends Controller
{
    public function index()
    {
        $rates = ForexRate::all();
        return view('admin.forex.index', compact('rates'));
    }

    public function create ()
    {
        return view('admin.forex.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'base_currency' => 'required|string|max:3',
            'target_currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:3',
            'rate' => 'required|numeric|min:0',
        ]);

        ForexRate::updateOrCreate(
            [
                // 'base_currency' => $request->base_currency,
                'base_currency' => 'INR',
                'target_currency' => $request->target_currency,
                'currency_symbol' => $request->currency_symbol
            ],
            ['rate' => $request->rate]
        );

        return redirect()->route('admin.forex.index')->with('success', 'Forex rate created successfully!');
    }

    public function destroy($id)
    {
        ForexRate::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Forex rate deleted successfully!');
    }

    public function edit ($id)
    {
        $rate = ForexRate::findOrFail($id);
        return view('admin.forex.edit', compact('rate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'base_currency' => 'required|string|max:3',
            'target_currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:3',
            'rate' => 'required|numeric|min:0',
        ]);

        $rate = ForexRate::findOrFail($id);
        $rate->update($request->all());
        return redirect()->route('admin.forex.index')->with('success', 'Forex rate updated successfully!');
    }


    public function editForexMode()
    {
        $currentMode = DB::table('settings')->where('key', 'forex_mode')->value('value') ?? 'auto';
        return view('admin.forex.mode', compact('currentMode'));
    }
    public function updateForexMode(Request $request)
    {
        $request->validate([
            'forex_mode' => 'required|in:auto,manual',
        ]);

        DB::table('settings')->updateOrInsert(
            ['key' => 'forex_mode'],
            ['value' => $request->forex_mode]
        );

        return redirect()->back()->with('success', 'Forex mode updated successfully.');
    }
}
