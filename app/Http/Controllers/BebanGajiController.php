<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BebanGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idku = Auth::user()->id;
        $beban_gajis = Pengeluaran::where('tipe_pengeluaran', 'beban_gaji')->where('id_jenis_usaha',$idku)->join('users','pengeluarans.id_karyawan','=','users.id')
        ->select('pengeluarans.id','nama','name','total_biaya','pengeluarans.tanggal_pengeluaran')
        ->orderBy('pengeluarans.id', 'DESC')->get();
        return view('admin.data-beban-gaji',compact('beban_gajis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $idku = Auth::user()->id;
        $kasir = User::where('parent_id',$idku)->get();
        return view('admin.create-beban-gaji',compact('kasir'));
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
                'nama_beban_gaji' => 'required|max:100|min:8',
                'kasir' => 'required',
                'total_gaji' => 'required|min:0',
                'tanggal' => 'required'
            ]
        );

        try {
            $id_kasir = Crypt::decryptString($request->kasir);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $total_gaji = str_replace(".", "", $request->total_gaji);

        try {
            $idku = Auth::user()->id;
            Pengeluaran::create([
                'tipe_pengeluaran' => 'beban_gaji',
                'nama' => $request->nama_beban_gaji,
                'total_biaya' => $total_gaji,
                'id_karyawan' => $id_kasir,
                'id_jenis_usaha' => $idku,
                'tanggal_pengeluaran' => $request->tanggal,
                'tipe' => 'pengeluaran'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan beban gaji');
        }
        return back()->with('success', 'Sukses menambahkan beban gaji');
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

        $idku = Auth::user()->id;
        $kasir = User::where('parent_id',$idku)->get();

        if ($id_dc) {
            $beban_gaji = Pengeluaran::where('id', $id_dc)->first();
            if ($beban_gaji) {
                return view('admin.edit-beban-gaji', compact('beban_gaji', 'id','kasir'));
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
                'nama_beban_gaji' => 'required|max:100|min:8',
                'kasir' => 'required',
                'total_gaji' => 'required|min:0',
                'tanggal' => 'required'
            ]
        );

        try {
            $id_kasir = Crypt::decryptString($request->kasir);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $total_gaji = str_replace(".", "", $request->total_gaji);

        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            Pengeluaran::where('id', $id)->update([
                'nama' => $request->nama_beban_gaji, 'total_biaya' => $total_gaji, 'id_karyawan' => $id_kasir, 'tanggal_pengeluaran' => $request->tanggal
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update beban gaji');
        }
        return back()->with('success', 'Sukses update beban gaji');
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
            $beban_gaji = Pengeluaran::where('id', $id_dc)->first();
            if ($beban_gaji) {
                try {
                    $beban_gaji->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus beban gaji');
                }
            }
        }
        return back()->with('success', 'Sukses hapus beban gaji');
    }
}
