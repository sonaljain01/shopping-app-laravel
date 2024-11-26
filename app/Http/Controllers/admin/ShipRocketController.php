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

    public function createOrder($order)
    {
        try {
            $products = [];
            foreach ($order->products as $product) {
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
                        'order_id' => $order->id,
                        'order_date' => $order->created_at->format('Y-m-d H:i:s'),
                        'pickup_location' => 'Home',
                        'channel_id' => '5780891',
                        'comment' => '',
                        'billing_customer_name' => $order->user->name,
                        'billing_last_name' => '',
                        'billing_address' => $order->address->address,
                        'billing_address_2' => '',
                        'billing_city' => $order->address->city,
                        'billing_pincode' => $order->address->zip,
                        'billing_state' => $order->address->state,
                        'billing_country' => $order->address->country,
                        'billing_email' => $order->user->email ?? '',
                        'billing_phone' => $order->user->phone ?? '',
                        'shipping_is_billing' => true,
                        'order_items' => $products,
                        'payment_method' => $order->payment_method == 'cod' ? 'postpaid' : 'prepaid',
                        'shipping_charges' => 0,
                        'sub_total' => $order->total,
                        'length' => 10,
                        'breadth' => 15,
                        'height' => 20,
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


    public function storeShipment($order)
    {
        $shiprocket = Shipment::updateOrCreate([
            'order_id' => $order['order_id'],
            'channel_order_id' => $order['channel_order_id'],
            'shipment_id' => $order['shipment_id'],
            'courier_name' => '',
            'status' => $order['status'],
            'pickup_address_id' => 1,
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
