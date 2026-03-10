<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('registration_number')->index();
            $table->string('make');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('vin')->nullable();
            $table->string('color')->nullable();
            $table->integer('mileage')->nullable();
            $table->date('mot_expiry')->nullable();
            $table->date('service_due')->nullable();
            $table->date('last_mot_check')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['garage_id', 'registration_number']);
            $table->index(['garage_id', 'mot_expiry']);
            $table->index(['garage_id', 'service_due']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};