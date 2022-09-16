<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class Admin
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if (!$request->ajax()) {
        //     return $this->error('Bad Request', 400);
        // }

        $userRole = $request->user()->role_id;

        if (intval($userRole) === 1) {
            return $next($request);
        } else {
            return  response()->json(["success"=>false,"message"=>'No tiene los permisos necesarios para acceder a los recursos solicitados'], 403);
        }
    }
}
