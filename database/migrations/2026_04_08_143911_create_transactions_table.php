<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('flight_id')->constrained('flights')->onDelete('cascade');
        $table->string('booking_code')->unique(); // Contoh: TRV-XYZ123
        $table->string('contact_name');
        $table->string('contact_phone');
        $table->string('contact_email');
        $table->integer('total_price');
        $table->enum('status', ['Unpaid', 'Paid', 'Cancelled'])->default('Unpaid'); // Status bayar
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
