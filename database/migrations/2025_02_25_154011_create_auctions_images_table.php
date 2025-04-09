<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auction_images', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('auction_request_id')
                  ->constrained('auction_requests')
                  ->onDelete('cascade');
            
            $table->string('image_path', 255); 
            $table->string('caption', 100)->nullable(); 
            $table->integer('order')->default(0); 

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_images');
    }
};