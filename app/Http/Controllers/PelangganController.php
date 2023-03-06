<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pengeluaran;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_jenis_usaha = Auth::user()->id;
        $pelanggans = Pelanggan::where('id_jenis_usaha',$id_jenis_usaha)->orderBy('id', 'DESC')->get();
        return view('admin.data-pelanggan',compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-pelanggan');
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
                'nama_pelanggan' => 'required|max:100|min:8',
                'kode_pelanggan' => 'required|min:8|max:20|unique:pelanggans',
                'alamat' => 'required|max:200|min:8'
            ]
        );

        try {
            $idku = Auth::user()->id;
            Pelanggan::create([
                'nama' => $request->nama_pelanggan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'alamat' => $request->alamat,
                'id_jenis_usaha' => $idku,
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan pelanggan');
        }
        return back()->with('success', 'Sukses menambahkan pelanggan');
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
            $pelanggan = Pelanggan::where('id', $id_dc)->first();
            if ($pelanggan) {
                return view('admin.edit-pelanggan', compact('pelanggan', 'id'));
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
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        request()->validate(
            [
                'nama_pelanggan' => 'required|max:100|min:8',
                'kode_pelanggan' => 'required|min:8|max:20|unique:pelanggans,kode_pelanggan,' . $id_dc,
                'alamat' => 'required|max:200|min:8'
            ]
        );
        
        try {
            Pelanggan::where('id',$id_dc)->update([
                'nama' => $request->nama_pelanggan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'alamat' => $request->alamat
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update pelanggan');
        }
        return back()->with('success', 'Sukses update pelanggan');
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
            $pelanggan = Pelanggan::where('id', $id_dc)->first();
            if ($pelanggan) {
                try {
                    $pelanggan->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus pelanggan');
                }
            }
        }
        return back()->with('success', 'Sukses hapus pelanggan');
    }

    public function bon_hutang($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $id_jenis_usaha = Auth::user()->id;
        $bon_hutangs = Pengeluaran::where('pengeluarans.id_jenis_usaha',$id_jenis_usaha)
        ->where('pengeluarans.id_pelanggan',$id_dc)
        ->where('pengeluarans.tipe_pengeluaran','hutang_pelanggan')
        ->leftJoin('pemasukans','pemasukans.id_hutang','=','pengeluarans.id')
        ->select('pengeluarans.nama','pengeluarans.total_biaya','pengeluarans.tanggal_pengeluaran','pengeluarans.status_hutang','pemasukans.tanggal_pemasukan')
        ->get();
        return view('admin.bon-hutang-pelanggan',compact('bon_hutangs','id'));
    }

    public function cetak_bon_hutang($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        $id_jenis_usaha = Auth::user()->id;
        $bon_hutangs = Pengeluaran::where('pengeluarans.id_jenis_usaha',$id_jenis_usaha)
        ->where('pengeluarans.id_pelanggan',$id_dc)
        ->where('pengeluarans.tipe_pengeluaran','hutang_pelanggan')
        ->leftJoin('pemasukans','pemasukans.id_hutang','=','pengeluarans.id')
        ->join('pelanggans','pelanggans.id','=','pengeluarans.id_pelanggan')
        ->join('users','users.id','=','pengeluarans.id_jenis_usaha')
        ->select('pengeluarans.nama','pengeluarans.total_biaya','pengeluarans.tanggal_pengeluaran','pengeluarans.status_hutang','pemasukans.tanggal_pemasukan','pelanggans.nama as nama_pelanggan','users.name as nama_jenis_usaha')
        ->get();
        return view('admin.cetak-bon-hutang-pelanggan',compact('bon_hutangs'));
    }
}
