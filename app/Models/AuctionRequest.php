<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'platform',
        'vin',
        'lot_number',
        'vehicle_location',
        'max_bid',
        'auction_url',
        'transport_quote',
        'carfax_quote',
        'comments',
        'service_fee',
        'stripe_price_id',
        'stripe_product_id',
        'status',
    ];

    protected $casts = [
        'transport_quote' => 'boolean',
        'carfax_quote' => 'boolean',
        'max_bid' => 'decimal:2',
        'service_fee' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getPlatforms(): array
    {
        return ['Copart', 'IAAI', 'Manheim', 'ACV Auctions'];
    }
}