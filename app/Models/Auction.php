<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'description',
        'starting_price',
        'current_price',
        'start_date',
        'end_date',
        'status',
        'reserve_fee_stripe_price_id',
        'vehicle_price_stripe_price_id',
        'reserve_fee',
        'vehicle_price',
    ];

    /**
     * Los atributos que deben ser ocultados para los arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Los atributos que deberían ser convertidos a fechas.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Los atributos que deberían ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'starting_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'reserve_fee' => 'decimal:2',
        'vehicle_price' => 'decimal:2',
        'status' => 'string',
    ];

    /**
     * Relación con el modelo Vehicle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Relación con el modelo AuctionImage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(AuctionImage::class);
    }
}