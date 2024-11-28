<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;
use App\Models\Shipment;
use App\Models\PickupAddress;


class ShipRocketController extends Controller
{
    protected $id;

    protected $channelId;
    protected $token;
    protected $pickup;

    public function __construct()
    {
        $this->token = env('SHIPROCKET_TOKEN');
        $this->channelId = env('SHIPROCKET_CHANNEL_ID');
    }


    // public function createOrder($data, $pickupaddress)
    // {
    //     $this->pickup = pickupAddress::where('tag', $pickupaddress)->first()->id;
    //     try {
    //         $products = [];
    //         $totalLength = 0;
    //         $totalBreadth = 0;
    //         $totalHeight = 0;
    //         // $totalWeight = 0;
    //         foreach ($data->products as $product) {
    //             $products[] = [
    //                 'name' => $product->product->title,
    //                 'sku' => $product->product->sku,
    //                 'units' => $product->quantity,
    //                 'selling_price' => $product->price / $product->quantity,
    //                 'discount' => '',
    //                 'tax' => '',
    //                 'hsn' => 441122,
    //             ];

    //             $totalLength += (int) $product->product->length;
    //             $totalBreadth += (int) $product->product->breath;
    //             $totalHeight += (int) $product->product->height;
    //             // $totalWeight += $product->product->weight;
    //         }
    //         // Make the API request to ShipRocket
    //         $api = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'Authorization' => 'Bearer ' . $this->token,
    //         ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
    //                     'order_id' => $data->id,
    //                     'order_date' => $data->created_at->format('Y-m-d H:i:s'),
    //                     'pickup_location' => $pickupaddress ?? 'home',
    //                     'channel_id' => $this->channelId,
    //                     'comment' => '',
    //                     'billing_customer_name' => $data->user->name,
    //                     'billing_last_name' => '',
    //                     'billing_address' => $data->address->address,
    //                     'billing_address_2' => '',
    //                     'billing_city' => $data->address->city,
    //                     'billing_pincode' => $data->address->pincode,
    //                     'billing_state' => $data->address->state,
    //                     'billing_country' => $data->address->country,
    //                     'billing_email' => $data->user->email,
    //                     'billing_phone' => $data->user->phone_number,
    //                     'shipping_is_billing' => true,
    //                     'shipping_customer_name' => '',
    //                     'shipping_last_name' => '',
    //                     'shipping_address' => '',
    //                     'shipping_address_2' => '',
    //                     'shipping_city' => '',
    //                     'shipping_pincode' => '',
    //                     'shipping_country' => '',
    //                     'shipping_state' => '',
    //                     'shipping_email' => '',
    //                     'shipping_phone' => '',
    //                     'order_items' => $products,
    //                     'payment_method' => $data->payment_method == 'cod' ? 'postpaid' : 'prepaid',
    //                     'shipping_charges' => 0,
    //                     'giftwrap_charges' => 0,
    //                     'transaction_charges' => 0,
    //                     'total_discount' => 0,
    //                     'sub_total' => $data->total,
    //                     'length' => $totalLength,
    //                     'breadth' => $totalBreadth,
    //                     'height' => $totalHeight,
    //                     'weight' => 2.5,
    //                 ]);

    //         // Check if the request was successful
    //         if ($api->successful()) {
    //             return $api; // Return the successful response as an array
    //         } else {
    //             // Log the error response for debugging
    //             Log::error('ShipRocket API Error: ' . $api->body());
    //             return [
    //                 'success' => false,
    //                 'message' => 'Failed to create order in ShipRocket. Error: ' . $api->body(),
    //             ];
    //         }
    //     } catch (\Exception $e) {
    //         // // Log the exception message
    //         // Log::error('Exception while creating ShipRocket order: ' . $e->getMessage());
    //         // return [
    //         //     'success' => false,
    //         //     'message' => 'An exception occurred while processing the order: ' . $e->getMessage(),
    //         // ];
    //         return $e->getMessage();
    //     }
    // }

    public function createOrder($data, $pickupAddressTag)
    {
        try {
            // echo collect($data);
            // dd();
            // Fetch the pickup address using the tag
            $pickupAddress = PickupAddress::where('tag', $pickupAddressTag)->first();

            if (!$pickupAddress) {
                return [
                    'success' => false,
                    'message' => 'Invalid pickup address tag provided.',
                ];
            }
            $this->pickup = $pickupAddress->id;

            $products = [];
            $totalLength = 0;
            $totalBreadth = 0;
            $totalHeight = 0;

            foreach ($data->orderItems as $product) {
                $products[] = [
                    'name' => $product->product->title,
                    'sku' => $product->product->sku,
                    'units' => $product->quantity,
                    'selling_price' => $product->price / $product->quantity,
                    'discount' => '',
                    'tax' => '',
                    'hsn' => 441122,
                ];

                $totalLength += (int) $product->product->length;
                $totalBreadth += (int) $product->product->breath;
                $totalHeight += (int) $product->product->height;
            }


            // Make the API request to ShipRocket
            $api = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
                        'order_id' => $data->id,
                        'order_date' => $data->created_at->format('Y-m-d H:i:s'),
                        'pickup_location' => $pickupAddress->tag,
                        'channel_id' => $this->channelId,
                        'comment' => '',
                        'billing_customer_name' => $data->billingAddress->name,
                        'billing_last_name' => '',
                        'billing_address' => $data->billingAddress->address_1,
                        'billing_address_2' => $data->billingAddress->address_2,
                        'billing_city' => $data->billingAddress->city,
                        'billing_pincode' => $data->billingAddress->zip,
                        'billing_state' => $data->billingAddress->state,
                        'billing_country' => $data->billingAddress->country,
                        'billing_email' => $data->user->email,
                        'billing_phone' => $data->user->phone_number,
                        'shipping_is_billing' => true,
                        'shipping_customer_name' => '',
                        'shipping_last_name' => '',
                        'shipping_address' => '',
                        'shipping_address_2' => '',
                        'shipping_city' => '',
                        'shipping_pincode' => '',
                        'shipping_country' => '',
                        'shipping_state' => '',
                        'shipping_email' => '',
                        'shipping_phone' => '',
                        'order_items' => $products,
                        'payment_method' => $data->payment_method == 'cod' ? 'postpaid' : 'prepaid',
                        'shipping_charges' => 0,
                        'giftwrap_charges' => 0,
                        'transaction_charges' => 0,
                        'total_discount' => 0,
                        'sub_total' => $data->total_amount,
                        'length' => $totalLength,
                        'breadth' => $totalBreadth,
                        'height' => $totalHeight,
                        'weight' => 2.5,
                    ]);

            return $api;
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function storeShipment($data)
    {
        $shiprocket = Shipment::updateOrCreate([
            'order_id' => $data['order_id'],
            'channel_order_id' => $data['channel_order_id'],
            'shipment_id' => $data['shipment_id'],
            'courier_name' => "",
            'status' => $data['status'],
            'pickup_address_id' => $this->pickup,
            'actual_weight' => null,
            'volumetric_weight' => null,
            'platform' => $this->channelId,
            'charges' => null,
        ]);

        if ($shiprocket) {
            return [
                'status' => true,
                'message' => 'Shipment created successfully',
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Shipment not created',
            ];
        }
    }


}
