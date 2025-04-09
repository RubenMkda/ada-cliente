<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carfax_memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); 
            $table->enum('type', ['limited', 'unlimited']);
            $table->integer('report_limit')->nullable();
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
        
        Schema::create('user_carfax_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('membership_id')->constrained('carfax_memberships');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('reports_used')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carfax_memberships');
    }
};
