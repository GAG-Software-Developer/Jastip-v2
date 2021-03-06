<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kode_transaksi');
            $table->string('kurir');
            $table->string('service');
            $table->integer('ongkir');
            $table->date('tanggal_penjualan');
            $table->date('tanggal_pengiriman')->nullable();
            $table->string('nomor_resi')->nullable();
            $table->integer('total_harga');
            $table->enum('status_penjualan_req', ['menunggu dikirim', 'dikirim', 'diterima', 'selesai', 'dibatalkan']);
            $table->integer('rating');
            $table->string('review')->nullable();
            $table->integer('id_penawaran');
            $table->integer('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan_requests');
    }
}
