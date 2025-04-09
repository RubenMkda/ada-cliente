<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auction_requests', function (Blueprint $table) {
            // Identificación y datos del cliente
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Detalles de la subasta externa
            $table->enum('platform', ['Copart', 'IAAI', 'Manheim', 'ACV Auctions']);
            $table->string('vin', 17);
            $table->string('lot_number', 50)->nullable();
            $table->string('vehicle_location', 255);
            $table->decimal('max_bid', 12, 2);
            $table->string('auction_url')->nullable();

            // Servicios adicionales
            $table->boolean('transport_quote')->default(false);
            $table->boolean('carfax_quote')->default(false);
            $table->text('comments')->nullable();
            
            // Lógica de pagos y estado
            $table->decimal('service_fee', 12, 2);
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_product_id')->nullable();
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            
            // Auditoría
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_requests');
    }
};