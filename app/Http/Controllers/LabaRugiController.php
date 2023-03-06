<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Transaksi_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    public function laporan_labarugi_produk()
    {
        return view('admin.produk_ver.laporan-laba-rugi');
    }

    public function laporan_labarugi_produk_print()
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

        $kases = Pengeluaran::select('tipe_pengeluaran as tipe', 'nama', 'total_biaya as total', 'tanggal_pengeluaran as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('tipe_pengeluaran', '=', 'beban_operasional')
            ->orWhere('tipe_pengeluaran', '=', 'beban_gaji')
            ->where('id_jenis_usaha', $id_jenis_usaha)->get();

        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.produk_ver.cetak-laporan-labarugi-produk', compact('transaksis', 'ketua', 'kases'));
    }

    public function filter_laporan_labarugi_produk_print(Request $request)
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
            ->whereBetween('pemasukans.tanggal_pemasukan', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();

        $kases = Pengeluaran::select('tipe_pengeluaran as tipe', 'nama', 'total_biaya as total', 'tanggal_pengeluaran as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('tipe_pengeluaran', '=', 'beban_operasional')
            ->orWhere('tipe_pengeluaran', '=', 'beban_gaji')
            ->whereBetween('tanggal_pengeluaran', [$request->tanggal_awal, $request->tanggal_akhir])
            ->where('id_jenis_usaha', $id_jenis_usaha)->get();

        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.produk_ver.cetak-laporan-labarugi-produk', compact('transaksis', 'ketua', 'kases'));
    }

    public function laporan_labarugi_jasa()
    {
        return view('admin.jasa_ver.laporan-laba-rugi');
    }

    public function laporan_labarugi_jasa_print()
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

        $kases = Pengeluaran::select('tipe_pengeluaran as tipe', 'nama', 'total_biaya as total', 'tanggal_pengeluaran as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('tipe_pengeluaran', '=', 'beban_operasional')
            ->orWhere('tipe_pengeluaran', '=', 'beban_gaji')
            ->where('id_jenis_usaha', $id_jenis_usaha)->get();

        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.jasa_ver.cetak-laporan-laba-rugi-jasa', compact('transaksis', 'ketua', 'kases'));
    }

    public function filter_laporan_labarugi_jasa_print(Request $request)
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
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)
            ->orderBy('pemasukans.tanggal_pemasukan')->orderBy('jam')
            ->whereBetween('pemasukans.tanggal_pemasukan', [$request->tanggal_awal, $request->tanggal_akhir])
            ->get();

        $kases = Pengeluaran::select('tipe_pengeluaran as tipe', 'nama', 'total_biaya as total', 'tanggal_pengeluaran as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('tipe_pengeluaran', '=', 'beban_operasional')
            ->orWhere('tipe_pengeluaran', '=', 'beban_gaji')
            ->whereBetween('tanggal_pengeluaran', [$request->tanggal_awal, $request->tanggal_akhir])
            ->where('id_jenis_usaha', $id_jenis_usaha)->get();

        $ketua = User::where('level', 'super-admin')->select('nama_kepala_usaha as ketua')->first();
        return view('admin.jasa_ver.cetak-laporan-laba-rugi-jasa', compact('transaksis', 'ketua', 'kases'));
    }
}
