<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pelanggan;
use App\Models\Jasa;
use App\Models\Transaksi_detail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TransaksiJasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $transaksis = Pemasukan::where('tipe_pemasukan', 'transaksi_jasa')->where('id_jenis_usaha', $id_jenis_usaha)->orderBy('id', 'DESC')->get();
        return view('kasir.jasa_ver.data-transaksi-jasa', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $jasas = Jasa::where('id_jenis_usaha', $id_jenis_usaha)->where('valid', 'yes')->orderBy('id', 'DESC')->get();
        return view('kasir.jasa_ver.create-transaksi', compact('jasas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_pelanggan(Request $request)
    {
        request()->validate(
            [
                'nama_transaksi' => 'required|min:8|max:100',
                'bayar' => 'required|min:1',
                'pelanggan' => 'required',
                'tanggal' => 'required'
            ]
        );

        $bayar = str_replace(".", "", $request->bayar);

        try {
            $id_pelanggan = Crypt::decryptString($request->pelanggan);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $semua_p = 0;
        foreach (session('cart-jasa') as $id => $jasa) {
            $total_p = $jasa['harga_pelanggan'] * $jasa['jumlah'];
            $semua_p += $total_p;
        }

        if ($bayar < $semua_p) {
            return back()->with('gagal', 'Uang Kurang');
        } else {
            try {
                $id_jenis_usaha = Auth::user()->parent_id;
                $id_kasir = Auth::user()->id;
                $kembali = $bayar-$semua_p;
                $id_transaksi = Pemasukan::tambah_header_transaksi_jasa($request->nama_transaksi, $semua_p, $id_jenis_usaha, $id_kasir, $request->tanggal,$bayar,$kembali);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal menambahkan transaksi');
            }
            foreach (session('cart-jasa') as $id => $jasa) {
                try {
                    $total_p = $jasa['harga_pelanggan'] * $jasa['jumlah'];
                    Transaksi_detail::create([
                        'id_transaksi' => $id_transaksi,
                        'id_jasa' => $id,
                        'id_pelanggan' => $id_pelanggan,
                        'jumlah' => $jasa['jumlah'],
                        'total' => $total_p,
                        'harga_jual_pelanggan_jasa' => $jasa['harga_pelanggan']
                    ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal menambahkan transaksi');
                }
            }
        }

        session()->forget('cart-jasa');
        return redirect()->route('transaksi-jasa')->with('success', 'Sukses menambahkan transaksi');
    }

    public function store_umum(Request $request)
    {
        request()->validate(
            [
                'nama_transaksi' => 'required|min:8|max:100',
                'bayar' => 'required|min:1',
                'tanggal' => 'required'
            ]
        );

        $bayar = str_replace(".", "", $request->bayar);

        $semua_u = 0;
        foreach (session('cart-jasa') as $id => $jasa) {
            $total_u = $jasa['harga_umum'] * $jasa['jumlah'];
            $semua_u += $total_u;
        }

        if ($bayar < $semua_u) {
            return back()->with('gagal', 'Uang Kurang');
        } else {
            try {
                $id_jenis_usaha = Auth::user()->parent_id;
                $id_kasir = Auth::user()->id;
                $kembali = $bayar-$semua_u;
                $id_transaksi = Pemasukan::tambah_header_transaksi_jasa($request->nama_transaksi, $semua_u, $id_jenis_usaha, $id_kasir, $request->tanggal,$bayar,$kembali);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal menambahkan transaksi');
            }
            foreach (session('cart-jasa') as $id => $jasa) {
                try {
                    $total_u = $jasa['harga_umum'] * $jasa['jumlah'];
                    Transaksi_detail::create([
                        'id_transaksi' => $id_transaksi,
                        'id_jasa' => $id,
                        'jumlah' => $jasa['jumlah'],
                        'total' => $total_u,
                        'harga_jual_umum_jasa' => $jasa['harga_umum']
                    ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal menambahkan transaksi');
                }
            }
        }

        session()->forget('cart-jasa');
        return redirect()->route('transaksi-jasa')->with('success', 'Sukses menambahkan transaksi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cart(Request $request)
    {
        request()->validate(
            [
                'id_jasa' => 'required',
                'jumlah' => 'required|min:1|numeric',
            ]
        );

        $id_jasa = $request->id_jasa;

        try {
            $id = Crypt::decryptString($id_jasa);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $jasas = Jasa::where('id', $id)->first();

        $cart = session()->get('cart-jasa');

        $jumlah = intval($request->jumlah);

        if (!$cart) {
            $cart = [
                $id => [
                    "nama" => $jasas->name,
                    "harga_umum" => $jasas->harga_umum,
                    "harga_pelanggan" => $jasas->harga_pelanggan,
                    "jumlah" => $jumlah
                ]
            ];

            session()->put('cart-jasa', $cart);
            return back()->with('success', 'Sukses menambahkan ke keranjang');
        }

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] += $jumlah;
            session()->put('cart-jasa', $cart);
            return back()->with('success', 'Sukses menambahkan ke keranjang');
        }

        $cart[$id] = [
            "nama" => $jasas->name,
            "harga_umum" => $jasas->harga_umum,
            "harga_pelanggan" => $jasas->harga_pelanggan,
            "jumlah" => $jumlah
        ];

        session()->put('cart-jasa', $cart);
        return back()->with('success', 'Sukses menambahkan ke keranjang');
    }

    public function reset_keranjang()
    {
        session()->forget('cart-jasa');
        return back()->with('success', 'Sukses reset keranjang');
    }

    public function bayar_pelanggan()
    {
        $cart = session()->get('cart-jasa');
        if (!$cart) {
            return back()->with('gagal', 'Keranjang kosong');
        }
        $id_jenis_usaha = Auth::user()->parent_id;
        $pelanggans = Pelanggan::where('id_jenis_usaha', $id_jenis_usaha)->orderBy('id', 'DESC')->get();
        return view('kasir.jasa_ver.bayar-pelanggan-transaksi', compact('pelanggans'));
    }

    public function bayar_umum()
    {
        $cart = session()->get('cart-jasa');
        if (!$cart) {
            return back()->with('gagal', 'Keranjang kosong');
        }

        return view('kasir.jasa_ver.bayar-umum-transaksi');
    }

    public function detail_transaksi($id)
    {
        try {
            $id = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $id_jenis_usaha = Auth::user()->parent_id;

        $details = Pemasukan::join('transaksi_details', 'pemasukans.id', '=', 'transaksi_details.id_transaksi')->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)
            ->join('jasas', 'transaksi_details.id_jasa', '=', 'jasas.id')
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->select(
                'jasas.name',
                'pelanggans.nama as nama_pelanggan',
                'transaksi_details.jumlah',
                'transaksi_details.total',
                'pemasukans.total as total_pemasukan',
                'transaksi_details.harga_jual_pelanggan_jasa as harga_pelanggan',
                'transaksi_details.harga_jual_umum_jasa as harga_umum'
            )
            ->where('pemasukans.id', $id)->get();
        return view('kasir.jasa_ver.detail-transaksi', compact('details'));
    }

    public function jasa_kasir()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $jasas = User::find($id_jenis_usaha)->jasas()->orderBy('id', 'DESC')->where('valid','yes')->get();
        return view('kasir.jasa_ver.data-jasa', compact('jasas'));
    }

    public function cetak_transaksi($id)
    {
        try {
            $id = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $id_jenis_usaha = Auth::user()->parent_id;
        $details = Pemasukan::join('transaksi_details', 'pemasukans.id', '=', 'transaksi_details.id_transaksi')->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)
            ->where('pemasukans.id', $id)
            ->join('jasas', 'transaksi_details.id_jasa', '=', 'jasas.id')
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->select(
                'jasas.name as nama_jasa',
                'pelanggans.nama as nama_pelanggan',
                'transaksi_details.jumlah',
                'transaksi_details.total',
                'pemasukans.total as total_pemasukan',
                'transaksi_details.harga_jual_pelanggan_jasa as harga_pelanggan',
                'transaksi_details.harga_jual_umum_jasa as harga_umum',
                'pemasukans.bayar',
                'pemasukans.kembali'
            )
            ->get();

        $transaksi = Pemasukan::where('pemasukans.id', $id)
            ->join('users', 'users.id', '=', 'pemasukans.id_kasir')
            ->select('pemasukans.nama', 'pemasukans.tanggal_pemasukan', 'users.name as nama_kasir')
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)->first();
        $nama_jenis_usaha = Pemasukan::where('pemasukans.id', $id)
            ->join('users', 'users.id', '=', 'pemasukans.id_jenis_usaha')
            ->select('users.name')
            ->where('pemasukans.id_jenis_usaha', $id_jenis_usaha)->first();
        return view('kasir.jasa_ver.cetak-transaksi', compact('details', 'transaksi', 'nama_jenis_usaha'));
    }
}
