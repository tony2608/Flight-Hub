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
        // INI BARIS SAKTINYA: Hapus tabel kalau kebetulan masih nyangkut di database
        Schema::dropIfExists('passengers');

        // Baru bikin tabel yang baru
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->enum('type', ['Dewasa', 'Anak', 'Bayi']); // Jenis penumpang
            $table->string('title')->nullable(); // Tn. / Ny. / Nn. (Khusus dewasa)
            $table->string('name');
            $table->string('identity_number')->nullable(); // NIK/Paspor (Anak & Bayi mungkin tidak punya)
            $table->date('date_of_birth')->nullable(); // Tanggal Lahir (Untuk anak & bayi)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};