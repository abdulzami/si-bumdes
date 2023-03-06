<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jasas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jasa');
            $table->string('name');
            $table->integer('harga_pelanggan');
            $table->integer('harga_umum');
            $table->enum('valid',['yes','no']);
            $table->unsignedBigInteger('id_jenis_usaha');
            $table->date('tanggal_jasa')->nullable();
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
        Schema::dropIfExists('jasas');
    }
}
