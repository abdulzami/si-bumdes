<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_jasa',
        'name',
        'harga_pelanggan',
        'harga_umum',
        'id_jenis_usaha',
        'valid',
        'tanggal_jasa'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
