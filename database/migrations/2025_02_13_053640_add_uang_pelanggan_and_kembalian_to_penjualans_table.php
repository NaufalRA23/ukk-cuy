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
        Schema::table('penjualans', function (Blueprint $table) {
            Schema::table('penjualans', function (Blueprint $table) {
                $table->decimal('uang_pelanggan', 15, 2)->after('total_harga');
                $table->decimal('kembalian', 15, 2)->after('uang_pelanggan');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            Schema::table('penjualans', function (Blueprint $table) {
                $table->dropColumn('uang_pelanggan');
                $table->dropColumn('kembalian');
            });
        });
    }
};
