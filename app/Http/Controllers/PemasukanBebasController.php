<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pemasukan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PemasukanBebasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idku = Auth::user()->id;
        $pemasukan_bebass = Pemasukan::where('tipe_pemasukan', 'pemasukan_bebas')->where('id_jenis_usaha',$idku)->orderBy('id', 'DESC')->get();;
        return view('admin.data-pemasukan-bebas', compact('pemasukan_bebass'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-pemasukan-bebas');
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
                'nama_pemasukan' => 'required|max:100|min:8',
                'total_dana_masuk' => 'required|min:0',
                'tanggal' => 'required',
            ]
        );

        $total_dana_masuk = str_replace(".", "", $request->total_dana_masuk);

        try {
            $idku = Auth::user()->id;
            Pemasukan::create([
                'tipe_pemasukan' => 'pemasukan_bebas',
                'nama' => $request->nama_pemasukan,
                'total' => $total_dana_masuk,
                'id_jenis_usaha' => $idku,
                'tanggal_pemasukan' => $request->tanggal,
                'tipe' => 'pemasukan'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan pemasukan bebas');
        }
        return back()->with('success', 'Sukses menambahkan pemasukan bebas');
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

        if ($id_dc) {
            $pemasukan_bebas = Pemasukan::where('id', $id_dc)->first();
            if ($pemasukan_bebas) {
                return view('admin.edit-pemasukan-bebas', compact('pemasukan_bebas', 'id'));
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
                'nama_pemasukan' => 'required|max:100|min:8',
                'total_dana_masuk' => 'required|min:0',
                'tanggal' => 'required',
            ]
        );

        $total_dana_masuk = str_replace(".", "", $request->total_dana_masuk);

        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            Pemasukan::where('id', $id)->update([
                'nama' => $request->nama_pemasukan, 'total' => $total_dana_masuk, 'tanggal_pemasukan' => $request->tanggal
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update pemasukan bebas');
        }
        return back()->with('success', 'Sukses update pemasukan bebas');
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
            $pemasukan = Pemasukan::where('id', $id_dc)->first();
            if ($pemasukan) {
                try {
                    $pemasukan->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus pemasukan bebas');
                }
            }
        }
        return back()->with('success', 'Sukses hapus pemasukan bebas');
    }
}
