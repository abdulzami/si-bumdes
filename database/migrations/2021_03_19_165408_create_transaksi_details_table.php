<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->unsignedBigInteger('id_jasa')->nullable();
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->integer('harga_jual_pelanggan_produk')->nullable();
            $table->integer('harga_jual_umum_produk')->nullable();
            $table->integer('harga_jual_pelanggan_jasa')->nullable();
            $table->integer('harga_jual_umum_jasa')->nullable();
            $table->integer('jumlah');
            $table->integer('total');
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('produks');
            $table->foreign('id_jasa')->references('id')->on('jasas');
            $table->foreign('id_transaksi')->references('id')->on('pemasukans');
            $table->foreign('id_pelanggan')->references('id')->on('pelanggans');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_details');
    }
}
