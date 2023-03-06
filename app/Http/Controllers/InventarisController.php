<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventarise;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $inventarises = Inventarise::where('id_jenis_usaha', $id)->orderBy('id', 'DESC')->get();
        $todayDate = Carbon::now()->format('Y-m-d');
        $inventarise = Inventarise::where('id_jenis_usaha', $id)->get();
        foreach ($inventarise as $index => $inventaris) {
            $plus = $inventaris->jumlah_penyusutan + 1;
            $futureDate = date('Y-m-d', strtotime('+' . $plus . ' year', strtotime($inventaris->tanggal)));
            if ($todayDate == $futureDate) {
                if ($inventaris->umur_ekonomis - $inventaris->jumlah_penyusutan > 0) {
                    Inventarise::susutkan($inventaris->id, $inventaris->jumlah_penyusutan);
                }
            }
        }

        return view('admin.data-inventaris', compact('inventarises'));
    }

    public function all()
    {
        $inventarises = Inventarise::orderBy('inventarises.id', 'DESC')
            ->join('users', 'users.id', '=', 'inventarises.id_jenis_usaha')
            ->get();
        $todayDate = Carbon::now()->format('Y-m-d');
        $inventarise = Inventarise::get();
        foreach ($inventarise as $index => $inventaris) {
            $plus = $inventaris->jumlah_penyusutan + 1;
            $futureDate = date('Y-m-d', strtotime('+' . $plus . ' year', strtotime($inventaris->tanggal)));
            if ($todayDate == $futureDate) {
                if ($inventaris->umur_ekonomis - $inventaris->jumlah_penyusutan > 0) {
                    Inventarise::susutkan($inventaris->id, $inventaris->jumlah_penyusutan);
                }
            }
        }
        return view('super-admin.data-inventaris', compact('inventarises'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-inventaris');
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
                'nama_barang' => 'required|max:100|min:8',
                'kode_barang' => 'required|max:20|min:2|unique:inventarises',
                'harga_barang' => 'required|min:0',
                'umur_ekonomis' => 'required|min:0|numeric',
                'beban_penyusutan' => 'required|min:0',
                'tanggal' => 'required',
            ]
        );

        $harga_barang = str_replace(".", "", $request->harga_barang);
        $beban_penyusutan = str_replace(".", "", $request->beban_penyusutan);

        if ($harga_barang < $beban_penyusutan * $request->umur_ekonomis) {
            return back()->with('gagal', 'Gagal menambahkan inventaris');
        } else {
            try {
                $idku = Auth::user()->id;
                Inventarise::create([
                    'nama_barang' => $request->nama_barang,
                    'kode_barang' => $request->kode_barang,
                    'harga' => $harga_barang,
                    'umur_ekonomis' => $request->umur_ekonomis,
                    'beban_penyusutan' => $beban_penyusutan,
                    'jumlah_penyusutan' => 0,
                    'tanggal' => $request->tanggal,
                    'id_jenis_usaha' => $idku,
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal menambahkan inventaris');
            }
            return back()->with('success', 'Sukses menambahkan inventaris');
        }
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
            $inventarise = Inventarise::where('id', $id_dc)->first();
            if ($inventarise) {
                if ($inventarise->jumlah_penyusutan > 0) {
                    abort(404);
                }
                return view('admin.edit-inventaris', compact('inventarise', 'id'));
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
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }

        request()->validate(
            [
                'nama_barang' => 'required|max:100|min:8',
                'kode_barang' => 'required|max:20|min:2|unique:inventarises,kode_barang,' . $id_dc,
                'harga_barang' => 'required|min:0',
                'umur_ekonomis' => 'required|min:0|numeric',
                'beban_penyusutan' => 'required|min:0',
                'tanggal' => 'required',
            ]
        );

        $harga_barang = str_replace(".", "", $request->harga_barang);
        $beban_penyusutan = str_replace(".", "", $request->beban_penyusutan);

        if ($harga_barang < $beban_penyusutan * $request->umur_ekonomis) {
            return back()->with('gagal', 'Gagal menambahkan inventaris');
        } else {
            try {
                $idku = Auth::user()->id;
                Inventarise::where('id', $id_dc)->update([
                    'nama_barang' => $request->nama_barang,
                    'kode_barang' => $request->kode_barang,
                    'harga' => $harga_barang,
                    'umur_ekonomis' => $request->umur_ekonomis,
                    'beban_penyusutan' => $beban_penyusutan,
                    'tanggal' => $request->tanggal,
                    'id_jenis_usaha' => $idku,
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                return back()->with('gagal', 'Gagal update inventaris');
            }
            return back()->with('success', 'Sukses update inventaris');
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
            $inventaris = Inventarise::where('id', $id_dc)->first();
            if ($inventaris) {
                try {
                    $inventaris->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus inventaris');
                }
            }
        }
        return back()->with('success', 'Sukses hapus inventaris');
    }

    public function print_all()
    {
        $inventarises = Inventarise::orderBy('inventarises.id', 'DESC')
            ->join('users', 'users.id', '=', 'inventarises.id_jenis_usaha')
            ->get();
        $ketua = Auth::user()->nama_kepala_usaha;
        return view('super-admin.print.inventaris', compact('inventarises', 'ketua'));
    }
}
