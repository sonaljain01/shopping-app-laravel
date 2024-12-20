<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'is_enabled'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
  

    public function pincode()
    {
        return $this->belongsTo(PinCode::class);
    }
}
