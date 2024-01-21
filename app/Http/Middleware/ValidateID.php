<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateID
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
        $id = $request->route('id');
        if (!isset($id) || !is_numeric($id)) {
            return response()->json(['Response' => 'O parâmetro de ID deve ser um inteiro não nulo.'], 400);
        }

        return $next($request);
    }
}
