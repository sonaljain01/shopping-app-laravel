<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Http;
use App\Models\Shipment;

class ShipRocketController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->token = env('SHIPROCKET_TOKEN');
    }

    /**
     * Create an order in ShipRocket.
     */
    public function createOrder($order)
    {
        try {
            $products = $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->product->title,
                    'sku' => $item->product->sku,
                    'units' => $item->quantity,
                    'selling_price' => $item->price,
                    'discount' => 0,
                    'tax' => 0,
                    'hsn' => 441122,
                ];
            })->toArray();

            $pickupAddress = $order->pickupAddress;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->post(env('SHIPROCKET_URL') . '/v1/external/orders/create/adhoc', [
                        'order_id' => $order->id,
                        'order_date' => now()->format('Y-m-d H:i:s'),
                        'pickup_location' => $pickupAddress->location_name ?? 'default',
                        'channel_id' => env('SHIPROCKET_CHANNEL_ID'),
                        'billing_customer_name' => $order->name,
                        'billing_address' => $order->billing_address,
                        'billing_city' => $order->city,
                        'billing_pincode' => $order->pincode,
                        'billing_state' => $order->state,
                        'billing_country' => $order->country,
                        'billing_phone' => $order->phone,
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
                        'payment_method' => $order->payment_method === 'cod' ? 'postpaid' : 'prepaid',
                        'shipping_charges' => 0,
                        'giftwrap_charges' => 0,
                        'transaction_charges' => 0,
                        'total_discount' => 0,
                        'sub_total' => $order->total,
                        'length' => 10,
                        'breadth' => 15,
                        'height' => 20,
                        'weight' => 2.5,
                    ]);

            if ($response->successful()) {
                $shipmentData = $response->json();
                $this->storeShipment($shipmentData, $order);

                return [
                    'status' => true,
                    'message' => 'Order created in ShipRocket',
                ];
            }

            Log::error('ShipRocket API Error: ' . $response->body());
            return [
                'status' => false,
                'message' => 'Failed to create order in ShipRocket',
            ];

        } catch (\Exception $e) {
            Log::error('ShipRocket Exception: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'An error occurred',
            ];
        }
    }

    /**
     * Store shipment details in the database.
     */
    public function storeShipment($shipmentData, $order)
    {
        $shiprocket = Shipment::updateOrCreate([
            'order_id' => $order->id,
        ], [
            'shipment_id' => $shipmentData['shipment_id'],
            'channel_order_id' => $shipmentData['order_id'],
            'status' => $shipmentData['status'],
            'pickup_address_id' => $order->pickup_address_id,
            'actual_weight' => $shipmentData['weight'],
            'volumetric_weight' => $shipmentData['volumetric_weight'],
            'chargeable_weight' => $shipmentData['chargeable_weight'],
            'platform' => env('SHIPROCKET_PLATFORM'),
            'courier_name' => '',
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
