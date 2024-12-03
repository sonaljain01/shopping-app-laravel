<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    

    protected $fillable = [
        'store_name',
        'store_address',
        'store_city',
        'store_state',
        'store_pin',
        'store_country',
        'store_phone',
        'gst_number',
        'tax_type',
    ];

    
}
