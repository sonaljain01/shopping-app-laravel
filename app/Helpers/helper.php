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

    if (! function_exists('getExchangeRate')) {

        function getExchangeRate($country)
        {
            try {
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

    // if (!function_exists('getExchangeRate')) {

    //     function getExchangeRate($country)
    //     {
    //         try {
    //             // Check if the exchange rate for this country is already cached.
    //             $cachedRate = Cache::get("exchange_rate_{$country}");
    //             if ($cachedRate) {
    //                 return $cachedRate;
    //             }

    //             // Fetch the currency code and symbol from restcountries API
    //             $currencyCode = null;
    //             $currencySymbol = null;
    //             $response = Http::get("https://restcountries.com/v3.1/alpha/{$country}");

    //             if (!$response->ok()) {
    //                 return [
    //                     'status' => false,
    //                     'data' => 'Failed to retrieve country information.',
    //                 ];
    //             }

    //             $data = $response->json();
    //             $currencies = $data[0]['currencies'] ?? [];

    //             if (empty($currencies)) {
    //                 return [
    //                     'status' => false,
    //                     'data' => 'Currency information not available.',
    //                 ];
    //             }

    //             $currencyCode = array_keys($currencies)[0] ?? 'INR';
    //             $currencySymbol = array_values($currencies)[0]['symbol'] ?? '₹';

    //             // Fetch the exchange rate from the exchangerate API
    //             $exchangeRateResponse = Http::get('https://v6.exchangerate-api.com/v6/3e5bd3a92bc6f53952cb8d41/latest/INR');

    //             if (!$exchangeRateResponse->successful()) {
    //                 return [
    //                     'status' => false,
    //                     'data' => 'Failed to retrieve exchange rate data.',
    //                 ];
    //             }

    //             $exchangeRateData = $exchangeRateResponse->json();
    //             $rate = $exchangeRateData['conversion_rates'][$currencyCode] ?? null;

    //             if (!$rate) {
    //                 return [
    //                     'status' => false,
    //                     'data' => 'Exchange rate for the selected currency not found.',
    //                 ];
    //             }

    //             // Prepare result
    //             $result = [
    //                 'status' => true,
    //                 'data' => $rate,
    //                 'currency' => $currencySymbol,
    //             ];

    //             // Cache the result for future requests (e.g., for 1 hour)
    //             Cache::put("exchange_rate_{$country}", $result, now()->addHour());

    //             return $result;
    //         } catch (\Exception $e) {
    //             return [
    //                 'status' => false,
    //                 'data' => 'An error occurred: ' . $e->getMessage(),
    //             ];
    //         }
    //     }
    // }
}
