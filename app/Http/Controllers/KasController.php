<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function kas()
    {
        return view('admin.kas');
    }

    public function kas_print()
    {
        $id_jenis_usaha = Auth::user()->id;
        $pemasukan = DB::table('pemasukans')
            ->select('tipe_pemasukan as tipe','nama','total','tanggal_pemasukan as tanggal','tipe as jenis_kas','created_at as jam')
            ->where('id_jenis_usaha',$id_jenis_usaha);

        $pengeluaran = DB::table('pengeluarans')
            ->select('tipe_pengeluaran as tipe','nama','total_biaya as total','tanggal_pengeluaran as tanggal','tipe as jenis_kas','created_at as jam')
            ->where('id_jenis_usaha',$id_jenis_usaha)
            ->union($pemasukan);

        $kases = $pengeluaran->orderBy('tanggal')->orderBy('jam')->get();
        $ketua = User::where('level','super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.print.kas',compact('kases','ketua'));
    }

    public function filter_kas_print(Request $request)
    {
        request()->validate(
            [
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
            ]
        );
        $id_jenis_usaha = Auth::user()->id;
        $pemasukan = DB::table('pemasukans')
            ->select('tipe_pemasukan as tipe','nama','total','tanggal_pemasukan as tanggal','tipe as jenis_kas','created_at as jam')
            ->where('id_jenis_usaha',$id_jenis_usaha);

        $pengeluaran = DB::table('pengeluarans')
            ->select('tipe_pengeluaran as tipe','nama','total_biaya as total','tanggal_pengeluaran as tanggal','tipe as jenis_kas','created_at as jam')
            ->where('id_jenis_usaha',$id_jenis_usaha)
            ->union($pemasukan);

        $kas = $pengeluaran->orderBy('tanggal')->orderBy('jam')
        ->get();

        $kases = $kas->whereBetween('tanggal',[$request->tanggal_awal,$request->tanggal_akhir]);
        $ketua = User::where('level','super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.print.kas',compact('kases','ketua'));
    }
}
