<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->date('service_date');
            $table->enum('service_type', ['interim', 'full', 'major', 'repair', 'diagnostic']);
            $table->text('description');
            $table->string('technician');
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('mileage')->nullable();
            $table->text('notes')->nullable();
            $table->json('parts_used')->nullable();
            $table->date('next_service_due')->nullable();
            $table->timestamps();
            
            $table->index(['vehicle_id', 'service_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};