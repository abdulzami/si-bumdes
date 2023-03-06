<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemasukansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_pemasukan');
            $table->string('nama');
            $table->integer('total');
            $table->unsignedBigInteger('id_jenis_usaha');
            $table->unsignedBigInteger('id_kasir')->nullable();
            $table->integer('bayar')->nullable();
            $table->integer('kembali')->nullable();
            $table->date('tanggal_pemasukan');
            $table->string('tipe');
            $table->unsignedBigInteger('id_hutang')->nullable();
            $table->timestamps();
            
            $table->foreign('id_kasir')->references('id')->on('users');
            $table->foreign('id_jenis_usaha')->references('id')->on('users');
            $table->foreign('id_hutang')->references('id')->on('pengeluarans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemasukans');
    }
}
