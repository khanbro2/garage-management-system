<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mot_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('test_date');
            $table->enum('result', ['pass', 'fail']);
            $table->integer('mileage')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('defects')->nullable();
            $table->string('mot_test_number')->nullable();
            $table->timestamps();
            
            $table->index(['vehicle_id', 'test_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mot_records');
    }
};