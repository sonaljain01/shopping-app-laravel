<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'guest_id',
        'billing_address_id',
        'payment_method',
        'status',
        'total_amount',
        'phone',
        'billing_address_id',
        'shipping_address_id',
        'currency_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderHistories()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
