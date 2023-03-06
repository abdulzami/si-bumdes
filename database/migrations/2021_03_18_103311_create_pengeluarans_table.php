<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->string('tipe_pengeluaran');
            $table->string('nama');
            $table->string('kode_produk')->nullable();
            $table->integer('harga_beli_satuan')->nullable();
            $table->integer('jumlah_beli')->nullable();
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->unsignedBigInteger('id_kasir')->nullable();
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->enum('status_hutang',['lunas','belum lunas'])->nullable();
            $table->integer('total_biaya');
            $table->unsignedBigInteger('id_jenis_usaha');
            $table->date('tanggal_pengeluaran');
            $table->string('tipe');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id')->on('pelanggans');
            $table->foreign('id_karyawan')->references('id')->on('users');
            $table->foreign('id_kasir')->references('id')->on('users');
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
        Schema::dropIfExists('pengeluarans');
    }
}
