<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function proses_login(Request $request)
    {
        request()->validate(
            [
                'username' => 'required|min:8|max:20',
                'password' => 'required|min:8',
            ]
            );
        
        $kredensil = $request->only('username','password');

        if(Auth::attempt($kredensil)){
            // $user = Auth::user();
            // if($user->level == 'admin'){
            //     return redirect()->intended('admin');
            // }elseif($user->level == 'kasir'){
            //     return redirect()->intended('kasir');
            // }elseif($user->level == 'super-admin'){
            //     return redirect()->intended('super-admin');
            // }
            
            return redirect('dashboard');
        }

        return redirect('/')->with('gagal','Username atau password kurang tepat !');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return Redirect('/');
    }
}


