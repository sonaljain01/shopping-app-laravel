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

    protected $token;

    public function __construct()
    {
        $this->token = env('SHIPROCKET_TOKEN');
    }


    public function createOrder($data, $pickupaddress)
    {
        try {
            $products = [];
            foreach ($data->products as $product) {
                $products[] = [
                    'name' => $product->product->title,
                    'sku' => $product->product->sku,
                    'units' => $product->quantity,
                    'selling_price' => $product->price,
                    'discount' => '',
                    'tax' => '',
                    'hsn' => 441122,
                ];
            }

            // Make the API request to ShipRocket
            $api = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ])->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', [
                        'order_id' => $data->id,
                        'order_date' => $data->created_at->format('Y-m-d H:i:s'),
                        'pickup_location' => $pickupaddress ?? 'home',
                        'channel_id' => '5780891',
                        'comment' => '',
                        'billing_customer_name' => $data->user->name,
                        'billing_last_name' => '',
                        'billing_address' => $data->address->address,
                        'billing_address_2' => '',
                        'billing_city' => $data->address->city,
                        'billing_pincode' => $data->address->pincode,
                        'billing_state' => $data->address->state,
                        'billing_country' => $data->address->country,
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
                        'sub_total' => $data->total,
                        'length' => $data->length,
                        'breadth' => $data->breadth,
                        'height' => $data->height,
                        'weight' => 2.5,
                    ]);

            // Check if the request was successful
            if ($api->successful()) {
                return $api->json(); // Return the successful response as an array
            } else {
                // Log the error response for debugging
                Log::error('ShipRocket API Error: ' . $api->body());
                return [
                    'success' => false,
                    'message' => 'Failed to create order in ShipRocket. Error: ' . $api->body(),
                ];
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Exception while creating ShipRocket order: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An exception occurred while processing the order: ' . $e->getMessage(),
            ];
        }
    }


    public function storeShipment($data)
    {
        $shiprocket = Shipment::updateOrCreate([
            'order_id' => $data['order_id'],
            'channel_order_id' => $data['channel_order_id'],
            'shipment_id' => $data['shipment_id'],
            'courier_name' => '',
            'status' => $data['status'],
            'pickup_address_id' => 2,
            'actual_weight' => '',
            'volumetric_weight' => '',
            'platform' => '5777349',
            'charges' => '',
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
