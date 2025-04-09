<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceFeeSeeder extends Seeder
{
    public function run()
    {
        DB::table('service_fees')->insert([
            [
                'min_amount' => 0,
                'max_amount' => 5999.99,
                'fee' => 299.00,
                'promo_code' => null,
                'valid_until' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_amount' => 6000,
                'max_amount' => 10999.99,
                'fee' => 399.00,
                'promo_code' => null,
                'valid_until' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_amount' => 11000,
                'max_amount' => 14999.99,
                'fee' => 499.00,
                'promo_code' => null,
                'valid_until' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_amount' => 15000,
                'max_amount' => null,
                'fee' => 599.00,
                'promo_code' => null,
                'valid_until' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}