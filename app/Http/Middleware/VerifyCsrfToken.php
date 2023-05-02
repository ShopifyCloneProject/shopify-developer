<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Closure;
use Auth;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "payment/*"
    ];

    /*public function handle(Request $request, Closure $next)
    {
        if($request->route()->getPrefix() == "/admin" && $request->route()->uri != "admin/login")
        {
            if(!Auth::check())
            {
                return redirect("/admin/login");
            }
        }

        return $next($request);
    }*/
}
