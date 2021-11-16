<?php

namespace App\Modules\User\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()){
            return redirect()->to('/');
        }
        return $next($request);
    }
}
