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
        Schema::create('flights', function (Blueprint $table) {
    $table->id();
    $table->foreignId('airline_id')->constrained('airlines');
    $table->foreignId('airplane_id')->constrained('airplanes');
    
    // Relasi ke tabel yang sama (airports) butuh perlakuan khusus
    $table->foreignId('departure_airport_id')->constrained('airports');
    $table->foreignId('arrival_airport_id')->constrained('airports');
    
    $table->string('flight_number');
    $table->dateTime('departure_time');
    $table->dateTime('arrival_time');
    $table->decimal('price', 12, 2);
    $table->integer('available_seats');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
