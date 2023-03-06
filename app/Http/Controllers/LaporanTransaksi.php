<?php

namespace App\Http\Controllers;

use App\Models\Transaksi_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanTransaksi extends Controller
{
    public function laporan_transaksi_produk()
    {
        return view('admin.produk_ver.laporan-transaksi-produk');
    }

    public function laporan_transaksi_produk_print()
    {
        $id_jenis_usaha = Auth::user()->id;
        $transaksis = Transaksi_detail::select(
            'pelanggans.nama as nama_pelanggan',
            'pengeluarans.nama as nama_produk',
            'transaksi_details.harga_jual_pelanggan_produk as harga_pelanggan_satuan',
            'transaksi_details.harga_jual_umum_produk as harga_umum_satuan',
            'transaksi_details.jumlah',
            'transaksi_details.total',
            'pengeluarans.harga_beli_satuan',
            'pemasukans.tanggal_pemasukan',
            'transaksi_details.created_at as jam'
        )
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->join('pemasukans', 'transaksi_details.id_transaksi', '=', 'pemasukans.id')
            ->join('produks', 'transaksi_details.id_produk', '=', 'produks.id')
            ->join('pengeluarans', 'produks.id_belanja', '=', 'pengeluarans.id')
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)
            ->orderBy('pemasukans.tanggal_pemasukan')->orderBy('jam')
            ->get();

        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.produk_ver.cetak-laporan-transaksi-produk', compact('transaksis', 'ketua'));
    }

    public function filter_laporan_transaksi_produk_print(Request $request)
    {

        request()->validate(
            [
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
            ]
        );

        $id_jenis_usaha = Auth::user()->id;
        $transaksise = Transaksi_detail::select(
            'pelanggans.nama as nama_pelanggan',
            'pengeluarans.nama as nama_produk',
            'transaksi_details.harga_jual_pelanggan_produk as harga_pelanggan_satuan',
            'transaksi_details.harga_jual_umum_produk as harga_umum_satuan',
            'transaksi_details.jumlah',
            'transaksi_details.total',
            'pengeluarans.harga_beli_satuan',
            'pemasukans.tanggal_pemasukan',
            'transaksi_details.created_at as jam'
        )
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->join('pemasukans', 'transaksi_details.id_transaksi', '=', 'pemasukans.id')
            ->join('produks', 'transaksi_details.id_produk', '=', 'produks.id')
            ->join('pengeluarans', 'produks.id_belanja', '=', 'pengeluarans.id')
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha);

        $transaksisen = $transaksise->orderBy('pemasukans.tanggal_pemasukan')->orderBy('jam')
            ->get();

        $transaksis = $transaksisen->whereBetween('tanggal_pemasukan', [$request->tanggal_awal, $request->tanggal_akhir]);

        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.produk_ver.cetak-laporan-transaksi-produk', compact('transaksis', 'ketua'));
    }

    public function laporan_transaksi_jasa()
    {
        return view('admin.jasa_ver.laporan-transaksi-jasa');
    }

    public function laporan_transaksi_jasa_print()
    {
        $id_jenis_usaha = Auth::user()->id;
        $transaksis = Transaksi_detail::select(
            'pelanggans.nama as nama_pelanggan',
            'jasas.name as nama_jasa',
            'transaksi_details.jumlah',
            'transaksi_details.total',
            'pemasukans.tanggal_pemasukan',
            'transaksi_details.created_at as jam'
        )
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->join('pemasukans', 'transaksi_details.id_transaksi', '=', 'pemasukans.id')
            ->join('jasas', 'transaksi_details.id_jasa', '=', 'jasas.id')
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)
            ->orderBy('pemasukans.tanggal_pemasukan')->orderBy('jam')
            ->get();
        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.jasa_ver.cetak-laporan-transaksi-jasa', compact('transaksis', 'ketua'));
    }

    public function filter_laporan_transaksi_jasa_print(Request $request)
    {
        request()->validate(
            [
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
            ]
        );

        $id_jenis_usaha = Auth::user()->id;
        $transaksis = Transaksi_detail::select(
            'pelanggans.nama as nama_pelanggan',
            'jasas.name as nama_jasa',
            'transaksi_details.jumlah',
            'transaksi_details.total',
            'pemasukans.tanggal_pemasukan',
            'transaksi_details.created_at as jam'
        )
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->join('pemasukans', 'transaksi_details.id_transaksi', '=', 'pemasukans.id')
            ->join('jasas', 'transaksi_details.id_jasa', '=', 'jasas.id')
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)->orderBy('jam')
            ->whereBetween('tanggal_pemasukan', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();
            
        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.jasa_ver.cetak-laporan-transaksi-jasa', compact('transaksis', 'ketua'));
    }
}
