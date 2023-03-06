<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jasa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class JasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idku = Auth::user()->id;
        $jasas = User::find($idku)->jasas()->orderBy('id', 'DESC')->get();
        return view('admin.jasa_ver.data-jasa', compact('jasas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jasa_ver.create-jasa');
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
                'nama_jasa' => 'required|max:100|min:8',
                'kode_jasa' => 'required|max:20|min:2|unique:jasas',
                'harga_umum' => 'required|min:0',
                'harga_pelanggan' => 'required|min:0',
                'tanggal' => 'required'
            ]
        );

        $harga_umum = str_replace(".", "", $request->harga_umum);
        $harga_pelanggan = str_replace(".", "", $request->harga_pelanggan);

        try {
            $idku = Auth::user()->id;
            Jasa::create([
                'name' => $request->nama_jasa,
                'valid' => 'no',
                'kode_jasa' => $request->kode_jasa,
                'harga_umum' => $harga_umum,
                'harga_pelanggan' => $harga_pelanggan,
                'id_jenis_usaha' => $idku,
                'tanggal_jasa' => $request->tanggal
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan jasa');
        }
        return back()->with('success', 'Sukses menambahkan jasa');
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
            $jasa = Jasa::where('id', $id_dc)->first();
            if ($jasa) {
                return view('admin.jasa_ver.edit-jasa', compact('jasa', 'id'));
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
        try {
            $id = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        request()->validate(
            [
                'nama_jasa' => 'required|max:100|min:8',
                'kode_jasa' => 'required|max:20|min:2|unique:jasas,kode_jasa,'.$id,
                'harga_umum' => 'required|min:0',
                'harga_pelanggan' => 'required|min:0',
                'tanggal' => 'required'
            ]
        );

        try {
            $idku = Auth::user()->id;
            Jasa::where('id',$id)->update([
                'name' => $request->nama_jasa,
                'valid' => 'no',
                'kode_jasa' => $request->kode_jasa,
                'harga_umum' => $request->harga_umum,
                'harga_pelanggan' => $request->harga_pelanggan,
                'id_jenis_usaha' => $idku,
                'tanggal_jasa' => $request->tanggal
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update jasa');
        }
        return back()->with('success', 'Sukses update jasa');
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
            $jasa = Jasa::where('id', $id_dc)->first();
            if ($jasa) {
                try {
                    $jasa->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus jasa');
                }
            }
        }
        return back()->with('success', 'Sukses hapus jasa');
    }

    public function validasi($id)
    {
        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            Jasa::where('id', $id)->update([
                'valid' => 'yes'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal validasi jasa');
        }
        return back()->with('success', 'Sukses validasi jasa');
    }

    public function edit_harga($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        $jasas = Jasa::where('id',$id_dc)->first();
        return view('admin.jasa_ver.edit-harga-jasa',compact('id','jasas'));
    }

    public function update_harga(Request $request,$id)
    {
        request()->validate(
            [
                'harga_pelanggan' => 'required|min:1',
                'harga_umum' => 'required|min:1',
            ]
        );
        $harga_pelanggan = str_replace(".", "", $request->harga_pelanggan);
        $harga_umum = str_replace(".", "", $request->harga_umum);
        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            Jasa::where('id', $id)->update([
                'harga_pelanggan' => $harga_pelanggan, 'harga_umum' => $harga_umum 
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update harga jasa');
        }
        return back()->with('success', 'Sukses update harga jasa');
    }
}
