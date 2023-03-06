<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'id_jasa',
        'id_pelanggan',
        'jumlah',
        'total',
        'harga_jual_pelanggan_produk',
        'harga_jual_umum_produk',
        'harga_jual_pelanggan_jasa',
        'harga_jual_umum_jasa',
    ];

}
