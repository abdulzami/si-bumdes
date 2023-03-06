<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_belanja',
        'stok',
        'harga_pelanggan_satuan',
        'harga_umum_satuan',
        'id_jenis_usaha',
        'tanggal_barang_masuk'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    static function kurangi_stok($jumlah,$id_produk)
    {
        $produk = Produk::find($id_produk);
        $stok_baru = $produk->stok - $jumlah;

        Produk::where('id',$id_produk)->update([
            'stok' => $stok_baru
        ]);
    }
}
