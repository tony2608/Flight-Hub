<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM jadi VARCHAR(50) biar sistem bebas masukin status apa aja
        DB::statement("ALTER TABLE transactions MODIFY status VARCHAR(50) DEFAULT 'Unpaid'");
    }

    public function down(): void
    {
        // Fitur rollback (kembali ke ENUM jika diperlukan)
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('Unpaid', 'Paid', 'Canceled') DEFAULT 'Unpaid'");
    }
};