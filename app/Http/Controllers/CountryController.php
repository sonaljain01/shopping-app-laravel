<?php

namespace App\Http\Controllers;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    // public function getDialCode(Request $request)
    // {
    //     $countryName = $request->get('country');
    //     $country = Country::where('name', $countryName)->first();

    //     if ($country) {
    //         return response()->json(['code' => $country->dial_code]);
    //     }

    //     return response()->json(['error' => 'Country not found'], 404);
    // }
    public function updateSelectedCountry(Request $request)
    {
        $country = Country::find($request->country_id);

        if ($country) {
            return response()->json([
                'country' => $country->name,
                'dial_code' => $country->dial_code,
            ]);
        }

        return response()->json(['error' => 'Country not found'], 404);
    }
}
