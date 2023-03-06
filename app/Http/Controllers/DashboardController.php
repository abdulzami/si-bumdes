<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Transaksi_detail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $id_jenis_usaha = Auth::user()->id;
        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::user()->level == 'admin') {
            //saldo
            $pengeluaran_saldo = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->sum('total_biaya');
            $pemasukan_saldo = Pemasukan::where('id_jenis_usaha', $id_jenis_usaha)->sum('total');
            //end_saldo

            //omset atau total laba
            $laba = 0;
            if (Auth::user()->wujud_usaha == "Produk") {
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
                foreach ($transaksis as $index => $transaksi) {
                    $laba += $transaksi->total - ($transaksi->harga_beli_satuan * $transaksi->jumlah);
                }
            } else if (Auth::user()->wujud_usaha == "Jasa") {
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
                foreach ($transaksis as $index => $transaksi) {
                    $laba += $transaksi->total;
                }
            }
            //end omset atau total laba

            //profit
            $pengeluaran_profit_beban_operasional = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->where('tipe_pengeluaran', 'beban_operasional')->sum('total_biaya');
            $pengeluaran_profit_hutang_pelanggan = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->where('tipe_pengeluaran', 'hutang_pelanggan')
                ->where('status_hutang', 'belum lunas')
                ->sum('total_biaya');
            $pengeluaran_profit_beban_gaji = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->where('tipe_pengeluaran', 'beban_gaji')->sum('total_biaya');
            $pengeluaran_profit = $pengeluaran_profit_beban_operasional + $pengeluaran_profit_beban_gaji;
            // $pengeluaran_profit = $pengeluaran_profit_beban_operasional + $pengeluaran_profit_hutang_pelanggan + $pengeluaran_profit_beban_gaji;
            //end profit
            
            return view('admin.dashboard', compact('pengeluaran_saldo', 'pemasukan_saldo', 'laba', 'pengeluaran_profit'));
        } else if (Auth::user()->level == 'super-admin') {
            $jumlah_jenis_usaha = User::where('level', 'admin')->count();
            $jumlah_kasir = User::where('level', 'kasir')->count();
            return view('super-admin.dashboard', compact('jumlah_kasir', 'jumlah_jenis_usaha'));
        } else if (Auth::user()->level == 'kasir') {

            return view('kasir.dashboard');
        } else {
            return abort('403');
        }
    }
}
