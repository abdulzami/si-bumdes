<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Pemasukan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Transaksi_detail;

class TransaksiProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $transaksis = Pemasukan::where('tipe_pemasukan', 'transaksi_produk')->where('id_jenis_usaha', $id_jenis_usaha)->orderBy('id', 'DESC')->get();
        return view('kasir.produk_ver.data-transaksi-produk', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $produks = Produk::where('produks.id_jenis_usaha', $id_jenis_usaha)->where('pengeluarans.tipe_pengeluaran', 'belanja_produk')
            ->join('pengeluarans', 'produks.id_belanja', '=', 'pengeluarans.id')->select('produks.id', 'pengeluarans.nama', 'produks.stok')->where('produks.stok', '!=', '0')
            ->orderBy('produks.id', 'DESC')->get();
        return view('kasir.produk_ver.create-transaksi', compact('produks'));
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
        foreach (session('cart') as $id => $produk) {
            $total_p = $produk['harga_pelanggan'] * $produk['jumlah'];
            $semua_p += $total_p;
        }

        if ($bayar < $semua_p) {
            return back()->with('gagal', 'Uang Kurang');
        }else{
            try {
                $id_jenis_usaha = Auth::user()->parent_id;
                $id_kasir = Auth::user()->id;
                $kembali = $bayar-$semua_p;
                $id_transaksi = Pemasukan::tambah_header_transaksi_produk($request->nama_transaksi, $semua_p, $id_jenis_usaha, $id_kasir, $request->tanggal,$bayar,$kembali);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal menambahkan transaksi');
            }
            foreach (session('cart') as $id => $produk) {
                try {
                    $total_p = $produk['harga_pelanggan'] * $produk['jumlah'];
                    Transaksi_detail::create([
                        'id_transaksi' => $id_transaksi,
                        'id_produk' => $id,
                        'id_pelanggan' => $id_pelanggan,
                        'jumlah' => $produk['jumlah'],
                        'total' => $total_p,
                        'harga_jual_pelanggan_produk' => $produk['harga_pelanggan']
                    ]);
    
                    Produk::kurangi_stok($produk['jumlah'], $id);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal menambahkan transaksi');
                }
            }
        }

        


        session()->forget('cart');
        return redirect()->route('transaksi-produk')->with('success', 'Sukses menambahkan transaksi');
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
        foreach (session('cart') as $id => $produk) {
            $total_u = $produk['harga_umum'] * $produk['jumlah'];
            $semua_u += $total_u;
        }
        $kembali = $bayar-$semua_u;
        if ($bayar < $semua_u) {
            return back()->with('gagal', 'Uang Kurang');
        } else {
            try {
                $id_jenis_usaha = Auth::user()->parent_id;
                $id_kasir = Auth::user()->id;
                $id_transaksi = Pemasukan::tambah_header_transaksi_produk($request->nama_transaksi, $semua_u, $id_jenis_usaha, $id_kasir, $request->tanggal,$bayar,$kembali);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal menambahkan transaksi');
            }
            foreach (session('cart') as $id => $produk) {
                try {
                    $total_u = $produk['harga_umum'] * $produk['jumlah'];
                    Transaksi_detail::create([
                        'id_transaksi' => $id_transaksi,
                        'id_produk' => $id,
                        'jumlah' => $produk['jumlah'],
                        'total' => $total_u,
                        'harga_jual_umum_produk' => $produk['harga_umum']
                    ]);

                    Produk::kurangi_stok($produk['jumlah'], $id);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal menambahkan transaksi');
                }
            }
        }

        session()->forget('cart');
        return redirect()->route('transaksi-produk')->with('success', 'Sukses menambahkan transaksi');
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
                'id_produk' => 'required',
                'jumlah' => 'required|min:1|numeric',
            ]
        );

        $id_produk = $request->id_produk;

        try {
            $id = Crypt::decryptString($id_produk);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $produks = Produk::where('produks.id', $id)->where('pengeluarans.tipe_pengeluaran', 'belanja_produk')
            ->join('pengeluarans', 'produks.id_belanja', '=', 'pengeluarans.id')->select('pengeluarans.nama as nama_produk', 'produks.harga_umum_satuan as harga_umum', 'produks.harga_pelanggan_satuan as harga_pelanggan', 'produks.stok')->first();

        $cart = session()->get('cart');

        $jumlah = intval($request->jumlah);

        if (isset($cart[$id])) {
            $jumlahe = $cart[$id]["jumlah"];
            $cari_cukup_pertama = $produks->stok - ($jumlahe + $jumlah);
            if ($cari_cukup_pertama < 0) {
                return back()->with('gagal', 'Stok tidak cukup');
            }
        }

        if (!$cart) {
            $cart = [
                $id => [
                    "nama" => $produks->nama_produk,
                    "harga_umum" => $produks->harga_umum,
                    "harga_pelanggan" => $produks->harga_pelanggan,
                    "jumlah" => $jumlah
                ]
            ];

            $cari_cukup = $produks->stok - $jumlah;
            if ($cari_cukup < 0) {
                return back()->with('gagal', 'Stok tidak cukup');
            }
            session()->put('cart', $cart);
            return back()->with('success', 'Sukses menambahkan ke keranjang');
        }

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] += $jumlah;
            session()->put('cart', $cart);
            return back()->with('success', 'Sukses menambahkan ke keranjang');
        }

        $cari_cukup = $produks->stok - $jumlah;
        if ($cari_cukup < 0) {
            return back()->with('gagal', 'Stok tidak cukup');
        }

        $cart[$id] = [
            "nama" => $produks->nama_produk,
            "harga_umum" => $produks->harga_umum,
            "harga_pelanggan" => $produks->harga_pelanggan,
            "jumlah" => $jumlah
        ];

        session()->put('cart', $cart);
        return back()->with('success', 'Sukses menambahkan ke keranjang');
    }

    public function reset_keranjang()
    {
        session()->forget('cart');
        return back()->with('success', 'Sukses reset keranjang');
    }

    public function bayar_pelanggan()
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return back()->with('gagal', 'Keranjang kosong');
        }
        $id_jenis_usaha = Auth::user()->parent_id;
        $pelanggans = Pelanggan::where('id_jenis_usaha', $id_jenis_usaha)->orderBy('id', 'DESC')->get();
        return view('kasir.produk_ver.bayar-pelanggan-transaksi', compact('pelanggans'));
    }

    public function bayar_umum()
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return back()->with('gagal', 'Keranjang kosong');
        }
        $id_jenis_usaha = Auth::user()->parent_id;
        return view('kasir.produk_ver.bayar-umum-transaksi');
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
            ->where('pemasukans.id', $id)
            ->join('produks', 'transaksi_details.id_produk', '=', 'produks.id')
            ->join('pengeluarans', 'pengeluarans.id', '=', 'produks.id_belanja')
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->select(
                'pengeluarans.nama as nama_produk',
                'pelanggans.nama as nama_pelanggan',
                'transaksi_details.jumlah',
                'transaksi_details.total',
                'pemasukans.total as total_pemasukan',
                'transaksi_details.harga_jual_pelanggan_produk as harga_pelanggan_satuan',
                'transaksi_details.harga_jual_umum_produk as harga_umum_satuan'
            )
            ->get();
        // return $details;
        return view('kasir.produk_ver.detail-transaksi', compact('details'));
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
            ->join('produks', 'transaksi_details.id_produk', '=', 'produks.id')
            ->join('pengeluarans', 'pengeluarans.id', '=', 'produks.id_belanja')
            ->leftJoin('pelanggans', 'transaksi_details.id_pelanggan', '=', 'pelanggans.id')
            ->select(
                'pengeluarans.nama as nama_produk',
                'pelanggans.nama as nama_pelanggan',
                'transaksi_details.jumlah',
                'transaksi_details.total',
                'pemasukans.total as total_pemasukan',
                'transaksi_details.harga_jual_pelanggan_produk as harga_pelanggan_satuan',
                'transaksi_details.harga_jual_umum_produk as harga_umum_satuan',
                'pemasukans.bayar',
                'pemasukans.kembali',
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
        return view('kasir.produk_ver.cetak-transaksi', compact('details', 'transaksi', 'nama_jenis_usaha'));
    }
}
