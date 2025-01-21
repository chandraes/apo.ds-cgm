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
        Schema::create('rekap_keluaran_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keluaran_id');
            $table->foreignId('ppn_keluaran_id')->constrained('ppn_keluarans')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->boolean('keranjang')->default(0)->after('total_ppn');
        });

        Schema::table('invoice_tagihans', function (Blueprint $table) {
            $table->bigInteger('ppn')->default(0)->after('tanggal');
            $table->boolean('lunas')->default(1)->after('total_tagihan');
            $table->bigInteger('penyesuaian')->default(0)->after('ppn');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('ppn_kumulatif')->default(1)->after('npwp');
        });

        Schema::table('invoice_ppns', function (Blueprint $table) {
            $table->bigInteger('penyesuaian')->default(0)->after('total_ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_keluaran_details');

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('keranjang');
        });

        Schema::table('invoice_tagihans', function (Blueprint $table) {
            $table->dropColumn('ppn');
            $table->dropColumn('lunas');
            $table->dropColumn('penyesuaian');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('ppn_kumulatif');
        });

        Schema::table('invoice_ppns', function (Blueprint $table) {
            $table->dropColumn('penyesuaian');
        });
    }
};
