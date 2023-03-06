<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::orderBy('id', 'DESC')->select('id', 'name')->where('level', 'admin')->get();
        for ($i = 0; $i < sizeof($data); $i++) {
            $kasirs = User::orderBy('id', 'DESC')->where('level', 'kasir')->where('parent_id', $data[$i]->id)->get();
            $data[$i]->kasir = $kasirs;
        }
        return view('super-admin.data-kasir', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_jenis_usaha)
    {
        try {
            $id_dc = Crypt::decryptString($id_jenis_usaha);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        if ($id_dc) {
            $jenis_usaha = User::where('id', $id_dc)->select('name')->first();
            if ($jenis_usaha) {
                return view('super-admin.create-kasir', compact('jenis_usaha', 'id_jenis_usaha'));
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id_jenis_usaha)
    {
        request()->validate(
            [
                'nama_kasir' => 'required|max:100|min:8',
                'username' => 'required|max:20|min:8',
                'password' => 'required|same:ulangi_password|min:8',
                'ulangi_password' => 'required|min:8'
            ]
        );
        try {
            $id_dc = Crypt::decryptString($id_jenis_usaha);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        try {
            User::create([
                'name' => $request->nama_kasir,
                'username' => $request->username,
                'password' =>  bcrypt($request->password),
                'level' => 'kasir',
                'parent_id' => $id_dc
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal menambahkan kasir');
        }
        return back()->with('success', 'Sukses menambahkan kasir');
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
            $kasir = User::where('id', $id_dc)->first();
            if ($kasir) {
                return view('super-admin.edit-kasir', compact('kasir', 'id'));
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
                'nama_kasir' => 'required|max:100|min:8',
                'username' => 'required|max:20|min:8|unique:users',
            ]
        );

        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            User::where('id', $id)->update([
                'name' => $request->nama_kasir, 'username' => $request->username
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal update kasir');
        }
        return back()->with('success', 'Sukses update kasir');
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
            $kasir = User::where('id', $id_dc)->first();
            if ($kasir) {
                try {
                    $kasir->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->with('gagal', 'Gagal hapus kasir');
                }
            }
        }
        return back()->with('success', 'Sukses hapus kasir');
    }

    public function reset_password($id)
    {
        try {
            $id_dc = Crypt::decryptString($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
            abort(404);
        }
        return view('super-admin.reset-password-kasir', compact('id'));
    }

    public function update_password(Request $request, $id)
    {
        request()->validate(
            [
                'password' => 'required|same:ulangi_password|min:8',
                'ulangi_password' => 'required|min:8'
            ]
        );
        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            User::where('id', $id)->update([
                'password' => bcrypt($request->password)
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal reset password kasir');
        }
        return back()->with('success', 'Sukses reset password kasir');
    }
}
