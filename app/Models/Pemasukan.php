<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_pemasukan',
        'nama',
        'id_produk',
        'jumlah',
        'total',
        'id_jenis_usaha',
        'id_kasir',
        'tanggal_pemasukan',
        'tipe',
        'id_hutang',
        'bayar',
        'kembali'
    ];

    static function tambah_header_transaksi_produk($nama, $total, $id_jenis_usaha, $id_kasir,$tanggal,$bayar,$kembali)
    {
        $data = Pemasukan::create([
            'tipe_pemasukan' => 'transaksi_produk',
            'nama' => $nama,
            'total' => $total,
            'id_jenis_usaha' => $id_jenis_usaha,
            'id_kasir' => $id_kasir,
            'tipe' => 'pemasukan',
            'tanggal_pemasukan' => $tanggal,
            'bayar' => $bayar,
            'kembali'=> $kembali
        ]);

        return $data->id;
    }

    static function tambah_header_transaksi_jasa($nama, $total, $id_jenis_usaha, $id_kasir,$tanggal,$bayar,$kembali)
    {
        $data = Pemasukan::create([
            'tipe_pemasukan' => 'transaksi_jasa',
            'nama' => $nama,
            'total' => $total,
            'id_jenis_usaha' => $id_jenis_usaha,
            'id_kasir' => $id_kasir,
            'tipe' => 'pemasukan',
            'tanggal_pemasukan' => $tanggal,
            'bayar' => $bayar,
            'kembali'=> $kembali
        ]);

        return $data->id;
    }
}
