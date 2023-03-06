<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventarise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'harga',
        'beban_penyusutan',
        'umur_ekonomis',
        'jumlah_penyusutan',
        'id_jenis_usaha',
        'tanggal'
    ];

    static function susutkan($id,$old_p)
    {
        Inventarise::where('id',$id)->update([
            'jumlah_penyusutan' => $old_p + 1
        ]);
    }
}
