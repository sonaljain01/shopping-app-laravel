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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class, 'billing_address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
