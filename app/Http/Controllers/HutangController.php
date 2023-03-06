<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class HutangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $hutangs = Pengeluaran::where('pengeluarans.id_jenis_usaha', $id_jenis_usaha)
            ->where('pengeluarans.tipe_pengeluaran', 'hutang_pelanggan')
            ->join('pelanggans', 'pengeluarans.id_pelanggan', '=', 'pelanggans.id')
            ->leftJoin('pemasukans', 'pemasukans.id_hutang', '=', 'pengeluarans.id')
            ->select('pengeluarans.id', 'pengeluarans.nama as nama_hutang', 'pelanggans.nama', 'pengeluarans.total_biaya', 'pengeluarans.status_hutang','pengeluarans.tanggal_pengeluaran','pemasukans.tanggal_pemasukan')
            ->orderBy('pengeluarans.id', 'DESC')->get();

        return view('kasir.data-hutang', compact('hutangs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id_jenis_usaha = Auth::user()->parent_id;
        $pelanggans = Pelanggan::where('id_jenis_usaha', $id_jenis_usaha)->orderBy('id', 'DESC')->get();
        return view('kasir.create-hutang', compact('pelanggans'));
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
                'nama_hutang' => 'required|min:8|max:100',
                'total_hutang' => 'required|min:1',
                'pelanggan' => 'required',
                'tanggal' => 'required'
            ]
        );

        $total_hutang = str_replace(".", "", $request->total_hutang);

        try {
            $id_pelanggan = Crypt::decryptString($request->pelanggan);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        try {
            $idku = Auth::user()->parent_id;
            $id_kasir = Auth::user()->id;
            Pengeluaran::create([
                'tipe_pengeluaran' => 'hutang_pelanggan',
                'nama' => $request->nama_hutang,
                'id_pelanggan' => $id_pelanggan,
                'status_hutang' => 'belum lunas',
                'total_biaya' => $total_hutang,
                'id_jenis_usaha' => $idku,
                'tanggal_pengeluaran' => $request->tanggal,
                'id_kasir' =>  $id_kasir,
                'tipe' => 'pengeluaran'
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan hutang');
        }
        return back()->with('success', 'Sukses menambahkan hutang');
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

        $idku = Auth::user()->parent_id;
        $pelanggans = Pelanggan::get();

        if ($id_dc) {
            $hutang = Pengeluaran::where('id', $id_dc)->where('tipe_pengeluaran', 'hutang_pelanggan')->first();
            if ($hutang) {
                if ($hutang->status_hutang == "belum lunas") {
                    return view('kasir.edit-hutang', compact('hutang', 'id', 'pelanggans'));
                } else {
                    abort(404);
                }
            } else {
                abort(404);
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
                'nama_hutang' => 'required|min:8|max:100',
                'total_hutang' => 'required|min:1',
                'pelanggan' => 'required',
                'tanggal' => 'required'
            ]
        );

        $total_hutang = str_replace(".", "", $request->total_hutang);

        try {
            $id_pelanggan = Crypt::decryptString($request->pelanggan);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        try {
            try {
                $id_hutang = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }

            Pengeluaran::where('id', $id_hutang)->update([
                'nama' => $request->nama_hutang,
                'id_pelanggan' => $id_pelanggan,
                'total_biaya' => $total_hutang,
                'tanggal_pengeluaran' => $request->tanggal,
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update hutang');
        }
        return back()->with('success', 'Sukses update hutang');
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
            $hutang = Pengeluaran::where('id', $id_dc)->where('tipe_pengeluaran', 'hutang_pelanggan')->first();
            if ($hutang) {
                if ($hutang->status_hutang == "belum lunas") {
                    try {
                        $hutang->delete();
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return back()->with('gagal', 'Gagal hapus hutang');
                    }
                }else{
                    abort(404);
                }
                
            }
        }
        return back()->with('success', 'Sukses hapus hutang');
    }

    public function lunas($id)
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        if ($id_dc) {
            $hutang = Pengeluaran::where('id', $id_dc)->where('tipe_pengeluaran', 'hutang_pelanggan')->first();
            if ($hutang) {
                if ($hutang->status_hutang == "belum lunas") {
                    try {
                        $idku = Auth::user()->parent_id;
                        $id_kasir = Auth::user()->id;
                        Pemasukan::create([
                            'tipe_pemasukan' => 'pemasukan_hutang_pelanggan',
                            'nama' => $hutang->nama,
                            'total' => $hutang->total_biaya,
                            'id_jenis_usaha' => $idku,
                            'tanggal_pemasukan' =>$todayDate,
                            'id_hutang' => $id_dc,
                            'id_kasir' => $id_kasir,
                            'tipe' => 'pemasukan'
                        ]);
    
                        Pengeluaran::where('id', $id_dc)->update([
                            'status_hutang' => 'lunas'
                        ]);
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return back()->with('gagal', 'Gagal lunasi hutang');
                    }
                }else{
                    abort(404);
                }
                
            }
        }
        return back()->with('success', 'Sukses lunasi hutang');
    }
}
