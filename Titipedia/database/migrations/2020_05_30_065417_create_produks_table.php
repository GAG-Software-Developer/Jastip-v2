<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->integer('stok');
            $table->integer('harga_jasa');
            $table->integer('harga_produk');
            $table->integer('berat');
            $table->string('keterangan')->nullable();
            $table->string('asal_pengiriman');
            $table->string('asal_negara');
            $table->integer('id_user');
            $table->integer('id_kategori');
            $table->enum('status_produk', ['aktif', 'tidak aktif']);
            $table->dateTime('estimasi_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produks');
    }
}
