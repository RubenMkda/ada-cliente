<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFee extends Model
{
    protected $fillable = [
        'min_amount', 'max_amount', 'fee', 'promo_code', 'valid_until'
    ];
}
