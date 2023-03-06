<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarises', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('kode_barang')->unique();
            $table->integer('harga');
            $table->integer('beban_penyusutan');
            $table->integer('umur_ekonomis');
            $table->integer('jumlah_penyusutan');
            $table->unsignedBigInteger('id_jenis_usaha');
            $table->date('tanggal');
            $table->timestamps();
            
            $table->foreign('id_jenis_usaha')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarises');
    }
}
