<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'username',
        'email',
        'company',
        'address_1',
        'address_2',
        'city',
        'zip',
        'phone',
        'additional_information',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
