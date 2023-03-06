<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Transaksi_detail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class JenisUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenis_usahas = User::orderBy('id', 'DESC')->where('level', 'admin')->get();
        return view('super-admin.data-jenis-usaha', compact('jenis_usahas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super-admin.create-jenis-usaha');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(
            [
                'nama_usaha' => 'required|max:100|min:8',
                'kepala_usaha' => 'required|max:100|min:8',
                'username' => 'required|max:20|min:8|unique:users',
                'password' => 'required|same:ulangi_password|min:8',
                'ulangi_password' => 'required',
                'wujud_usaha' => 'required'
            ]
        );

        try {
            User::create([
                'name' => $request->nama_usaha,
                'nama_kepala_usaha' => $request->kepala_usaha,
                'username' => $request->username,
                'password' =>  bcrypt($request->password),
                'level' => 'admin',
                'wujud_usaha' => $request->wujud_usaha
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan jenis usaha');
        }
        return back()->with('success', 'Sukses menambahkan jenis usaha');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        if ($id_dc) {
            $jenis_usaha = User::where('id', $id_dc)->first();
            if ($jenis_usaha) {
                return view('super-admin.edit-jenis-usaha', compact('jenis_usaha', 'id'));
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate(
            [
                'nama_usaha' => 'required|max:100|min:8',
                'username' => 'required|max:20|min:8',
                'kepala_usaha' => 'required|max:100|min:8'
            ]
        );

        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            User::where('id', $id)->update([
                'name' => $request->nama_usaha, 'username' => $request->username, 'nama_kepala_usaha' => $request->kepala_usaha
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update jenis usaha');
        }
        return back()->with('success', 'Sukses update jenis usaha');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        if ($id_dc) {
            $jenis_usaha = User::where('id', $id_dc)->first();
            if ($jenis_usaha) {
                try {
                    $jenis_usaha->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus jenis usaha');
                }
            }
        }
        return back()->with('success', 'Sukses hapus jenis usaha');
    }

    public function reset_password($id)
    {
        return view('super-admin.reset-password-jenis-usaha', compact('id'));
    }

    public function update_password(Request $request, $id)
    {
        request()->validate(
            [
                'password' => 'required|same:ulangi_password|min:8',
                'ulangi_password' => 'required|min:8'
            ]
        );
        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            User::where('id', $id)->update([
                'password' => bcrypt($request->password)
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal reset password jenis usaha');
        }
        return back()->with('success', 'Sukses reset password jenis usaha');
    }

    public function saldo_omset_profit($id)
    {
        try {
            $id_jenis_usaha = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $jenis_usaha = User::where('id', $id_jenis_usaha)->first();
        $nama_usaha = $jenis_usaha->name;
        //saldo
        $pengeluaran_saldo = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->sum('total_biaya');
        $pemasukan_saldo = Pemasukan::where('id_jenis_usaha', $id_jenis_usaha)->sum('total');
        //end_saldo

        //omset atau total laba
        $laba = 0;
        if ($jenis_usaha->wujud_usaha == "Produk") {
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
        } else if ($jenis_usaha->wujud_usaha == "Jasa") {
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
        // $pengeluaran_profit_hutang_pelanggan = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->where('tipe_pengeluaran', 'hutang_pelanggan')
        //     ->where('status_hutang', 'belum lunas')
        //     ->sum('total_biaya');
        $pengeluaran_profit_beban_gaji = Pengeluaran::where('id_jenis_usaha', $id_jenis_usaha)->where('tipe_pengeluaran', 'beban_gaji')->sum('total_biaya');

        // $pengeluaran_profit = $pengeluaran_profit_beban_operasional + $pengeluaran_profit_hutang_pelanggan + $pengeluaran_profit_beban_gaji;
        $pengeluaran_profit = $pengeluaran_profit_beban_operasional + $pengeluaran_profit_beban_gaji;

        return view('super-admin.saldo-omset-profit', compact('pengeluaran_saldo', 'pemasukan_saldo', 'laba', 'pengeluaran_profit', 'nama_usaha'));
    }

    public function data_kas($id)
    {
        try {
            $id_jenis_usaha = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $jenis_usaha = User::where('id', $id_jenis_usaha)->first();
        $nama_usaha = $jenis_usaha->name;

        $pemasukan = DB::table('pemasukans')
            ->select('tipe_pemasukan as tipe', 'nama', 'total', 'tanggal_pemasukan as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('id_jenis_usaha', $id_jenis_usaha);

        $pengeluaran = DB::table('pengeluarans')
            ->select('tipe_pengeluaran as tipe', 'nama', 'total_biaya as total', 'tanggal_pengeluaran as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('id_jenis_usaha', $id_jenis_usaha)
            ->union($pemasukan);

        $kases = $pengeluaran->orderBy('tanggal')->orderBy('jam')->get();

        return view('super-admin.data-kas', compact('nama_usaha', 'kases', 'id_jenis_usaha', 'id'));
    }

    public function filter_data_kas(Request $request, $id)
    {
        request()->validate(
            [
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
            ]
        );

        try {
            $id_jenis_usaha = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $pemasukan = DB::table('pemasukans')
            ->select('tipe_pemasukan as tipe', 'nama', 'total', 'tanggal_pemasukan as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('id_jenis_usaha', $id_jenis_usaha);

        $pengeluaran = DB::table('pengeluarans')
            ->select('tipe_pengeluaran as tipe', 'nama', 'total_biaya as total', 'tanggal_pengeluaran as tanggal', 'tipe as jenis_kas', 'created_at as jam')
            ->where('id_jenis_usaha', $id_jenis_usaha)
            ->union($pemasukan);

        $kas = $pengeluaran->orderBy('tanggal')->orderBy('jam')
            ->get();

        $kases2 = $kas->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        return back()->with('kas', $kases2);
        // return view('super-admin.data-kas', compact('nama_usaha','kases'));
    }

    public function data_transaksi($id)
    {
        try {
            $id_jenis_usaha = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $jenis_usaha = User::where('id', $id_jenis_usaha)->first();
        $nama_usaha = $jenis_usaha->name;
        $wujud_usaha = $jenis_usaha->wujud_usaha;
        if ($wujud_usaha == 'Produk') {
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

            return view('super-admin.data-transaksi-produk', compact('transaksis', 'nama_usaha', 'id'));
        } else if ($wujud_usaha == 'Jasa') {
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

            return view('super-admin.data-transaksi-jasa', compact('transaksis', 'nama_usaha', 'id'));
        } else {
            abort(404);
        }
    }

    public function filter_data_transaksi(Request $request, $id)
    {
        request()->validate(
            [
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
            ]
        );

        try {
            $id_jenis_usaha = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        $jenis_usaha = User::where('id', $id_jenis_usaha)->first();
        $nama_usaha = $jenis_usaha->name;
        $wujud_usaha = $jenis_usaha->wujud_usaha;
        if ($wujud_usaha == 'Produk') {
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

            $transaksis2 = $transaksisen->whereBetween('tanggal_pemasukan', [$request->tanggal_awal, $request->tanggal_akhir]);
            return back()->with('transaksi', $transaksis2);
        } else if ($wujud_usaha == 'Jasa') {
            $transaksis2 = Transaksi_detail::select(
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
            return back()->with('transaksi', $transaksis2);
        } else {
            abort(404);
        }
    }
}
