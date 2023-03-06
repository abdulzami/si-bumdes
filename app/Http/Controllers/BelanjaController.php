<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BelanjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idku = Auth::user()->id;
        $belanjas = User::find($idku)->pengeluarans()->leftJoin('produks','produks.id_belanja','=','pengeluarans.id')->where('tipe_pengeluaran','belanja_produk')
        ->select('pengeluarans.id as id_pengeluaran','pengeluarans.nama','pengeluarans.kode_produk','pengeluarans.harga_beli_satuan','pengeluarans.jumlah_beli','pengeluarans.total_biaya','produks.id_belanja','pengeluarans.tanggal_pengeluaran')        
        ->orderBy('pengeluarans.id', 'DESC')->get();
        return view('admin.produk_ver.data-belanja-produk',compact('belanjas'));
        // return $belanjas;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.produk_ver.create-belanja-produk');
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
                'nama_produk' => 'required|max:100|min:8',
                'kode_produk' => 'required|max:20|min:2',
                'harga_beli_satuan' => 'required|min:0',
                'jumlah_beli' => 'required|min:0',
                'tanggal' => 'required',
            ]
        );

        $harga_beli_satuan = str_replace(".", "", $request->harga_beli_satuan);

        try {
            $idku = Auth::user()->id;
            Pengeluaran::create([
                'tipe_pengeluaran' => 'belanja_produk',
                'nama' => $request->nama_produk,
                'kode_produk' => $request->kode_produk,
                'harga_beli_satuan' => $harga_beli_satuan,
                'jumlah_beli' => $request->jumlah_beli,
                'total_biaya' =>$harga_beli_satuan*$request->jumlah_beli,
                'id_jenis_usaha' => $idku,
                'tanggal_pengeluaran' => $request->tanggal,
                'tipe' => 'pengeluaran'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan belanja produk');
        }
        return back()->with('success', 'Sukses menambahkan belanja produk');
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
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $belanjas = Pengeluaran::where('pengeluarans.id',$id_dc)->leftJoin('produks','produks.id_belanja','=','pengeluarans.id')->where('tipe_pengeluaran','belanja_produk')
        ->select('produks.id_belanja')->first();
        if ($belanjas->id_belanja == null) {
            if ($id_dc) {
                $belanjas = Pengeluaran::where('id', $id_dc)->first();
                if ($belanjas) {
                    return view('admin.produk_ver.edit-belanja-produk', compact('belanjas', 'id'));
                }
            }
        }else{
            abort(404);
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
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        $belanjas = Pengeluaran::where('pengeluarans.id',$id_dc)->leftJoin('produks','produks.id_belanja','=','pengeluarans.id')->where('tipe_pengeluaran','belanja_produk')
        ->select('produks.id_belanja')->first();
        if ($belanjas->id_belanja == null) {
            request()->validate(
                [
                    'nama_produk' => 'required|max:100|min:8',
                    'kode_produk' => 'required|max:20|min:2',
                    'harga_beli_satuan' => 'required|min:0',
                    'jumlah_beli' => 'required|min:0',
                    'tanggal' => 'required',
                ]
            );
    
            $harga_beli_satuan = str_replace(".", "", $request->harga_beli_satuan);
    
            try {
                $idku = Auth::user()->id;
                Pengeluaran::where('id',$id_dc)->update([
                    'tipe_pengeluaran' => 'belanja_produk',
                    'nama' => $request->nama_produk,
                    'kode_produk' => $request->kode_produk,
                    'harga_beli_satuan' => $harga_beli_satuan,
                    'jumlah_beli' => $request->jumlah_beli,
                    'total_biaya' => $harga_beli_satuan*$request->jumlah_beli,
                    'id_jenis_usaha' => $idku,
                    'tanggal_pengeluaran' => $request->tanggal
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal update belanja produk');
            }
            return back()->with('success', 'Sukses update belanja produk');
        }else{
            abort(404);
        }
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
            $pengeluaran = Pengeluaran::where('id', $id_dc)->first();
            if ($pengeluaran) {
                try {
                    $pengeluaran->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus pengeluaran belanja produk');
                }
            }
        }
        return back()->with('success', 'Sukses hapus pengeluaran pengeluaran belanja produk');
    }

    public function to_produk_page($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $produk = Produk::where('id_belanja',$id_dc)->first();

        if($produk)
        {
            abort(404);
        }else{
            return view('admin.produk_ver.to-produk-belanja-produk',compact('id'));
        }
        
    }
}
