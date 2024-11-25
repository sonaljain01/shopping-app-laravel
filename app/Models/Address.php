<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'name',
        'company',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'phone',
        'country',
        'additional_information',
        'type',
        'is_default',
        'user_id',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
