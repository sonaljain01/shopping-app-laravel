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


    public function createOrder($data, $pickupAddressTag)
    {
        try {

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

    // public function storeShipment($data)
    // {

    //     $shiprocket = Shipment::updateOrCreate([
    //         'order_id' => $data['order_id'],
    //         'channel_order_id' => $data['channel_order_id'],
    //         'shipment_id' => $data['shipment_id'],
    //         'courier_name' => "",
    //         'status' => $data['status'],
    //         'pickup_address_id' => $this->pickup,
    //         'actual_weight' => null,
    //         'volumetric_weight' => null,
    //         'platform' => $this->channelId,
    //         'charges' => null,
    //     ]);

    //     if ($shiprocket) {
    //         return [
    //             'status' => true,
    //             'message' => 'Shipment created successfully',
    //         ];
    //     } else {
    //         return [
    //             'status' => false,
    //             'message' => 'Shipment not created',
    //         ];
    //     }
    // }

    public function storeShipment($data)
    {
        // Validate input data
        $requiredKeys = ['order_id', 'channel_order_id', 'shipment_id', 'status'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                return [
                    'status' => false,
                    'message' => "Missing required key: $key",
                ];
            }
        }

        // Create or update the shipment
        $shiprocket = Shipment::updateOrCreate(
            [
                'order_id' => $data['order_id'],
                'channel_order_id' => $data['channel_order_id'],
                'shipment_id' => $data['shipment_id'],
            ],
            [
                'courier_name' => $data['courier_name'] ?? "",
                'status' => $data['status'],
                'pickup_address_id' => $this->pickup ?? null,
                'actual_weight' => $data['actual_weight'] ?? null,
                'volumetric_weight' => $data['volumetric_weight'] ?? null,
                'platform' => $this->channelId ?? null,
                'charges' => $data['charges'] ?? null,
            ]
        );

        // Return success or failure message
        if ($shiprocket) {
            return [
                'status' => true,
                'message' => 'Shipment created successfully',
            ];
        }

        return [
            'status' => false,
            'message' => 'Shipment not created',
        ];
    }

}
