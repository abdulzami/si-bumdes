<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BebanOperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idku = Auth::user()->id;
        $beban_operasionals = Pengeluaran::where('tipe_pengeluaran', 'beban_operasional')->where('id_jenis_usaha',$idku)->orderBy('id', 'DESC')->get();
        return view('admin.data-beban-operasional',compact('beban_operasionals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-beban-operasional');
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
                'nama_beban_operasional' => 'required|max:100|min:8',
                'total_biaya' => 'required|min:0',
                'tanggal' => 'required'
            ]
        );

        $total_biaya = str_replace(".", "", $request->total_biaya);

        try {
            $idku = Auth::user()->id;
            Pengeluaran::create([
                'tipe_pengeluaran' => 'beban_operasional',
                'nama' => $request->nama_beban_operasional,
                'total_biaya' => $total_biaya,
                'id_jenis_usaha' => $idku,
                'tanggal_pengeluaran' => $request->tanggal,
                'tipe' => 'pengeluaran'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan beban operasional');
        }
        return back()->with('success', 'Sukses menambahkan beban operasional');
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
            $beban_operasional = Pengeluaran::where('id', $id_dc)->first();
            if ($beban_operasional) {
                return view('admin.edit-beban-operasional', compact('beban_operasional', 'id'));
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
                'nama_beban_operasional' => 'required|max:100|min:8',
                'total_biaya' => 'required|min:0',
            ]
        );

        $total_biaya = str_replace(".", "", $request->total_biaya);

        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            Pengeluaran::where('id', $id)->update([
                'nama' => $request->nama_beban_operasional, 'total_biaya' => $total_biaya
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update beban operasional');
        }
        return back()->with('success', 'Sukses update beban operasional');
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
            $beban_operasional = Pengeluaran::where('id', $id_dc)->first();
            if ($beban_operasional) {
                try {
                    $beban_operasional->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus beban operasional');
                }
            }
        }
        return back()->with('success', 'Sukses hapus beban operasional');
    }

    public function laporan()
    {
        $data = Pemasukan::where('id_jenis_usaha',3)->select('id','')->get();
        return $data;
    }
}
