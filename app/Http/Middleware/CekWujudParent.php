<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CekWujudParent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $wujud_usaha = User::find($user->parent_id);

        if ($wujud_usaha->wujud_usaha == $role) {
            return $next($request);
        } else {
            return redirect('/')->with('error', 'Anda tidak punya akses !');
        }
    }
}
