<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use PDO;

class ProfilController extends Controller
{
    public function index()
    {
        $idku = Auth::user()->id;
        $kasir = User::select('parent_id')->where('id',$idku)->first();
        if($kasir->parent_id == null){
            return view('profil');
        }else{
            $parent_id = $kasir->parent_id;
            $nama_usaha = User::select('name','wujud_usaha')->where('id',$parent_id)->first();
             return view('profil',compact('nama_usaha'));
        }
        
        abort(404);

    }

    public function update_identitas(Request $request, $id)
    {
        if(Auth::user()->level == "admin" || Auth::user()->level == "super-admin")
        {
            request()->validate(
                [
                    'nama' => 'required|max:100|min:8',
                    'kepala_usaha' => 'required|max:100|min:8',
                ]
            );
        }else{
            request()->validate(
                [
                    'nama' => 'required|max:100|min:8',
                ]
            );
        }
       
        try {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $ex) {
                abort(404);
            }
            if(Auth::user()->level == "admin" || Auth::user()->level == "super-admin"){
                User::where('id', $id)->update([
                    'name' => $request->nama,
                    'nama_kepala_usaha' => $request->kepala_usaha
                ]);
            }else{
                User::where('id', $id)->update([
                    'name' => $request->nama
                ]);
            }
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('gagal', 'Gagal ubah identitas');
        }
        return back()->with('success', 'Sukses ubah identitas');
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
            return back()->with('gagal', 'Gagal ubah password');
        }
        return back()->with('success', 'Sukses ubah password');
    }
}
