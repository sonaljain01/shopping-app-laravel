<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PickupAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address_1',
        'address_2',
        'city',
        'state',
        'country',
        'zip',
        'phone',
        'is_default',
        'tags',
    ];
}
