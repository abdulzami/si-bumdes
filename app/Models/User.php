<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'level',
        'password',
        'parent_id',
        'wujud_usaha',
        'nama_kepala_usaha'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class,'id_jenis_usaha');
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class,'id_jenis_usaha');
    }

    public function jasas()
    {
        return $this->hasMany(Jasa::class,'id_jenis_usaha');
    }
}
