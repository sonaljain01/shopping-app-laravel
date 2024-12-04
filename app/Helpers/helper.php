<?php
use App\Models\Category;

function getCategories()
{
    // Category::orderBy('name', 'ASC')->get();
    return Category::with('children')->where('showHome', 'Yes')->orderBy('id', 'desc')->take(4)->get();
}

if (!function_exists('getTelCode')) {
    function getTelCode($data)
    {
        $response = Http::get("https://restcountries.com/v3.1/alpha/{$data}");
        if ($response->ok()) {
            $data = $response->json();
            $telephoneCode = $data[0]['idd']['root'] . $data[0]['idd']['suffixes'][0];
            return [
                'code' => $telephoneCode ?: 'N/A',
                'status' => true,
            ];
        } else {
            return [
                'code' => 'Country not found.',
                'status' => false,
            ];
        }
    }
}
if (!function_exists('getLocationInfo')) {
    function getLocationInfo(string $ip): array
    {
        try {
            $response = Http::get("http://ipinfo.io/{$ip}/json");
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['bogon']) && $data['bogon'] == 1) {
                    return [
                        'status' => false,
                        'message' => 'Bogon IP address detected. Unable to determine location.',
                    ];
                }
                $country = $data['country'] ?? 'IN';
                return [
                    'status' => true,
                    // 'data' => $data,
                    'data' => ['country' => $country],
                ];
            }
            return [
                'status' => false,
                'message' => 'Unable to retrieve location data.',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'An error occurred while fetching location data.',
            ];
        }
    }

    if (!function_exists('getExchangeRate')) {

        function getExchangeRate($country)
        {
            try {

                // $mode = DB::table('settings')->where('key', 'forex_mode')->value('value') ?? 'auto';

                // if ($mode === 'manual') {
                //     // Manual mode: Fetch rate from the database
                //     $currencyDetails = Http::get("https://restcountries.com/v3.1/alpha/{$country}");
                //     if ($currencyDetails->ok()) {
                //         $data = $currencyDetails->json();
                //         $currencyCode = array_keys($data[0]['currencies'])[0] ?? 'INR';
                //         $currencySymbol = array_values($data[0]['currencies'])[0]['symbol'] ?? '₹';

                //         $manualRate = DB::table('forex_rates')
                //             ->where('base_currency', 'INR')
                //             ->where('target_currency', $currencyCode)
                //             ->first();

                //         if ($manualRate) {
                //             return [
                //                 'status' => true,
                //                 'data' => $manualRate->rate,
                //                 'currency' => $manualRate->currency_symbol,
                //             ];
                //         }
                //         return [
                //             'status' => false,
                //             'data' => 'Manual rate not found.',
                //         ];
                //     }
                // }


                $currencyCode = null;
                $currencySymbol = null;
                $response = Http::get("https://restcountries.com/v3.1/alpha/{$country}");
                if ($response->ok()) {
                    $data = $response->json();
                    $currencies = $data[0]['currencies'];
                    $currencyCode = array_keys($currencies)[0] ?? 'INR';
                    $currencySymbol = array_values($currencies)[0]['symbol'] ?? '₹';
                }
                $res = Http::get('https://v6.exchangerate-api.com/v6/3e5bd3a92bc6f53952cb8d41/latest/INR');
                if ($res->successful()) {
                    $data = $res->json();
                    return [
                        'status' => true,
                        'data' => $data['conversion_rates'][$currencyCode],
                        'currency' => $currencySymbol,
                    ];
                }
                return [
                    'status' => false,
                    'data' => $res->json()['unsupported-code'],
                ];
            } catch (\Exception $e) {
                return [
                    'status' => false,
                    'data' => $e->getMessage(),
                ];
            }
        }
    }

    function getCurrencyCodeFromCountry($country)
    {
        $response = Http::get("https://restcountries.com/v3.1/alpha/{$country}");
        if ($response->ok()) {
            $data = $response->json();
            return array_keys($data[0]['currencies'])[0] ?? 'INR';
        }
        return 'INR'; // Default to INR
    }
    // if (!function_exists('getExchangeRate')) {
    //     function getExchangeRate($country)
    //     {
    //         try {
    //             // Step 1: Fetch forex mode
    //             $mode = DB::table('settings')->where('key', 'forex_mode')->value('value') ?? 'auto';

    //             if ($mode === 'manual') {
    //                 // Manual mode: Fetch rate from the database
    //                 $currencyDetails = Http::get("https://restcountries.com/v3.1/alpha/{$country}");
    //                 if ($currencyDetails->ok()) {
    //                     $data = $currencyDetails->json();
    //                     $currencyCode = array_keys($data[0]['currencies'])[0] ?? 'INR';
    //                     $currencySymbol = array_values($data[0]['currencies'])[0]['symbol'] ?? '₹';

    //                     $manualRate = DB::table('forex_rates')
    //                         ->where('base_currency', 'INR')
    //                         ->where('target_currency', $currencyCode)
    //                         ->first();

    //                     if ($manualRate) {
    //                         return [
    //                             'status' => true,
    //                             'data' => $manualRate->rate,
    //                             'currency' => $manualRate->currency_symbol,
    //                         ];
    //                     }
    //                     return [
    //                         'status' => false,
    //                         'data' => 'Manual rate not found.',
    //                     ];
    //                 }
    //             }

    //             // Automatic mode: Fetch rate from APIs
    //             $response = Http::get("https://restcountries.com/v3.1/alpha/{$country}");
    //             if ($response->ok()) {
    //                 $data = $response->json();
    //                 $currencies = $data[0]['currencies'];
    //                 $currencyCode = array_keys($currencies)[0] ?? 'INR';
    //                 $currencySymbol = array_values($currencies)[0]['symbol'] ?? '₹';
    //             }

    //             $res = Http::get('https://v6.exchangerate-api.com/v6/YOUR_API_KEY/latest/INR');
    //             if ($res->successful()) {
    //                 $data = $res->json();
    //                 return [
    //                     'status' => true,
    //                     'data' => $data['conversion_rates'][$currencyCode],
    //                     'currency' => $currencySymbol,
    //                 ];
    //             }

    //             return [
    //                 'status' => false,
    //                 'data' => $res->json()['unsupported-code'] ?? 'Error fetching automatic rate.',
    //             ];
    //         } catch (\Exception $e) {
    //             return [
    //                 'status' => false,
    //                 'data' => $e->getMessage(),
    //             ];
    //         }
    //     }
    // }


}
