<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->level == 'kasir'){
            $idku = Auth::user()->parent_id;
        }else{
            $idku = Auth::user()->id;
        }
        
        $produks = User::find($idku)->produks()->join('pengeluarans','produks.id_belanja','=','pengeluarans.id')->orderBy('produks.id', 'DESC')->get();
        return view('admin.produk_ver.data-produk', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function create()
    // {
    //     return view('admin.produk_ver.create-produk');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $belanja = Pengeluaran::find($id_dc);
        request()->validate(
            [
                'harga_pelanggan_satuan' => 'required|min:0',
                'harga_umum_satuan' => 'required|min:0',
                'tanggal' => 'required'
            ]
        );

        $harga_pelanggan_satuan = str_replace(".", "", $request->harga_pelanggan_satuan);
        $harga_umum_satuan = str_replace(".", "", $request->harga_umum_satuan);

        if($belanja->harga_beli_satuan > $harga_pelanggan_satuan || $belanja->harga_beli_satuan > $harga_umum_satuan)
        {
            return back()->with('gagal', 'Gagal menambahkan ke list produk, harga jual harus kurang dari harga beli');
        }else{
            $produk = Produk::where('id_belanja',$id_dc)->first();
            if($produk)
            {
                abort(404);
            }else{
                try {
                    Produk::create([
                        'id_belanja' => $belanja->id,
                        'stok' => $belanja->jumlah_beli,
                        'harga_umum_satuan' => $harga_umum_satuan,
                        'harga_pelanggan_satuan' => $harga_pelanggan_satuan,
                        'id_jenis_usaha' => $belanja->id_jenis_usaha,
                        'tanggal_barang_masuk' => $request->tanggal
                    ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal menambahkan ke list produk');
                }
            }
            
        }

        return redirect()->route('produk')->with('success', 'Sukses menambahkan ke lists produk');
    }

    public function edit_harga($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        $produks = Produk::where('id',$id_dc)->first();
        return view('admin.produk_ver.edit-harga-produk',compact('id','produks'));
    }

    public function update_harga(Request $request,$id)
    {
        request()->validate(
            [
                'harga_pelanggan_satuan' => 'required|min:1',
                'harga_umum_satuan' => 'required|min:1',
            ]
        );
        $harga_pelanggan_satuan = str_replace(".", "", $request->harga_pelanggan_satuan);
        $harga_umum_satuan = str_replace(".", "", $request->harga_umum_satuan);
        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            Produk::where('id', $id)->update([
                'harga_pelanggan_satuan' => $harga_pelanggan_satuan, 'harga_umum_satuan' => $harga_umum_satuan 
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update harga produk');
        }
        return back()->with('success', 'Sukses update harga produk');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     try {
    //         $id_dc = Crypt::decryptString($id);
    //     } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
    //         abort(404);
    //     }

    //     $valid = Produk::where('id', $id_dc)->select('valid')->first();
    //     if ($valid->valid == 'no') {
    //         if ($id_dc) {
    //             $produk = Produk::where('id', $id_dc)->first();
    //             if ($produk) {
    //                 return view('admin.produk_ver.edit-produk', compact('produk', 'id'));
    //             }
    //         }
    //     }else{
    //         abort(404);
    //     }
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $id = Crypt::decryptString($id);
    //     } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
    //         abort(404);
    //     }
    //     $valid = Produk::where('id', $id)->select('valid')->first();
    //     if ($valid->valid == 'no') {
    //         request()->validate(
    //             [
    //                 'nama_produk' => 'required|max:100|min:8',
    //                 'kode_produk' => 'required|max:20|min:2|unique:produks,kode_produk,' . $id,
    //                 'satuan' => 'required|max:100|min:2',
    //                 'jumlah_pack' => 'required|min:0',
    //                 'jumlah_satuan_per_pack' => 'required|min:0',
    //                 'harga_beli_per_pack' => 'required|min:0',
    //                 'harga_beli_per_satuan' => 'required|min:0',
    //                 'harga_jual_pelanggan_per_pack' => 'required|min:0',
    //                 'harga_jual_pelanggan_per_satuan' => 'required|min:0',
    //                 'harga_jual_umum_per_pack' => 'required|min:0',
    //                 'harga_jual_umum_per_satuan' => 'required|min:0'
    //             ]
    //         );

    //         try {
    //             $idku = Auth::user()->id;
    //             Produk::where('id', $id)->update([
    //                 'name' => $request->nama_produk,
    //                 'kode_produk' => $request->kode_produk,
    //                 'nama_satuan' => $request->satuan,
    //                 'stok' => $request->jumlah_pack * $request->jumlah_satuan_per_pack,
    //                 'jumlah_per_pack' => $request->jumlah_satuan_per_pack,
    //                 'harga_beli_per_pack' => $request->harga_beli_per_pack,
    //                 'harga_beli_per_satuan' => $request->harga_beli_per_satuan,
    //                 'harga_jual_pelanggan_per_pack' => $request->harga_jual_pelanggan_per_pack,
    //                 'harga_jual_pelanggan_per_satuan' => $request->harga_jual_pelanggan_per_satuan,
    //                 'harga_jual_umum_per_pack' => $request->harga_jual_umum_per_pack,
    //                 'harga_jual_umum_per_satuan' => $request->harga_jual_umum_per_satuan,
    //                 'id_jenis_usaha' => $idku,
    //                 'valid' => 'no'
    //             ]);
    //         } catch (\Illuminate\Database\QueryException $ex) {
    //             return back()->with('gagal', 'Gagal update produk');
    //         }
    //         return back()->with('success', 'Sukses update produk');
    //     } else {
    //         abort(404);
    //     }
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     try {
    //         $id_dc = Crypt::decryptString($id);
    //     } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
    //         abort(404);
    //     }
    //     if ($id_dc) {
    //         $produk = Produk::where('id', $id_dc)->first();
    //         if ($produk) {
    //             try {
    //                 $produk->delete();
    //             } catch (\Illuminate\Database\QueryException $ex) {
    //                 return back()->with('gagal', 'Gagal hapus produk');
    //             }
    //         }
    //     }
    //     return back()->with('success', 'Sukses hapus produk');
    // }
    // public function validasi($id)
    // {
    //     try {
    //         try {
    //             $id = Crypt::decryptString($id);
    //         } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
    //             abort(404);
    //         }
    //         Produk::where('id', $id)->update([
    //             'valid' => 'yes'
    //         ]);
    //     } catch (\Illuminate\Database\QueryException $ex) {
    //         return back()->with('gagal', 'Gagal validasi produk');
    //     }
    //     return back()->with('success', 'Sukses validasi produk');
    // }
}
