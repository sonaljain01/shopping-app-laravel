<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ForexRate;
class ForexRateController extends Controller
{
    // public function index()
    // {
    //     $forexRates = ForexRate::all();
    //     return view('admin.forex_rates.index', compact('forexRates'));
    // }

    // public function update(Request $request)
    // {
    //     foreach ($request->rates as $id => $rate) {
    //         ForexRate::where('id', $id)->update(['rate' => $rate]);
    //     }

    //     return redirect()->back()->with('success', 'Forex rates updated successfully.');
    // }
}
