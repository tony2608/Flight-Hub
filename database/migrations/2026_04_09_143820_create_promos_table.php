<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. HANCURKAN tabel promos yang lama biar bersih sampai ke akar
        Schema::dropIfExists('promos');

        // 2. BANGUN ULANG tabel promos dengan kolom yang super komplit
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('badge')->nullable();
            $table->text('description')->nullable();
            $table->string('code')->nullable();
            $table->string('image')->nullable();
            $table->integer('discount')->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};