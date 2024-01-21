<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateDisapprovalRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $observation = $request->input('observation'); // Pegando campo

        // Verificando campo
        if ($observation) {
            return $next($request);
        } else {
            return response()->json(['Response' => 'O campo Observação é obrigatório!']);
        }
    }
}
