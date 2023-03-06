<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_pengeluaran',
        'nama',
        'kode_produk',
        'harga_beli_satuan',
        'jumlah_beli',
        'id_karyawan',
        'total_biaya',
        'id_jenis_usaha',
        'id_pelanggan',
        'status_hutang',
        'tanggal_pengeluaran',
        'tipe'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
