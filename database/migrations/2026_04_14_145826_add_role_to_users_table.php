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
        Schema::table('users', function (Blueprint $table) {
            // Kita pakai tipe ENUM biar isinya cuma bisa 5 jabatan ini aja
            $table->enum('role', ['user', 'admin', 'manager', 'staff', 'hrd'])
                  ->default('user')
                  ->after('password');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
