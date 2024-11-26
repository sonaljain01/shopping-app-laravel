<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier_name',
        'pickup_address_id',
        'actual_weight',
        'volumetric_weight',
        'chargeable_weight',
        'platform',
        'shipment_id',
        'status',
        'channel_order_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pickupAddress()
    {
        return $this->belongsTo(PickupAddress::class);
    }
}
