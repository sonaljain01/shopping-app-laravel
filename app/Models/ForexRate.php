<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ForexRate extends Model
{
    use HasFactory;

    protected $fillable = ['base_currency', 'target_currency', 'rate', 'currency_symbol'];
}
