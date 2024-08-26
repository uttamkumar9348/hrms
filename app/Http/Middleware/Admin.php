<?php

namespace App\Http\Middleware;

use App\Helpers\AppHelper;
use App\Models\Role;
use Closure;;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth()->check() ||
            !in_array($request->user()->role->name,AppHelper::getBackendLoginAuthorizedRole())
        ){
           $request->session()->invalidate();
           return redirect()->route('admin.login');
        }
        return $next($request);
    }
}



