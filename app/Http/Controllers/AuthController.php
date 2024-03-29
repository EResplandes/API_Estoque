<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function login(Request $request)
    {

        $query = $this->authService->login($request); // Consulta para realizar autenticação
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function logout(Request $request)
    {

        $query = $this->authService->logout($request); // Consulta para realizar invalidação do token
        return response()->json(['Response' => 'Usuário deslogado com sucesso!']); // Retornando resposta

    }

    public function check(Request $request)
    {

        $query = $this->authService->checkToken($request); // Consulta para verificar se token está valido
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function first(Request $request)
    {

        $query = $this->authService->firstAccess($request); // Consulta para alterar status do primeiro acesso como false
        return response()->json(['Response' => $query]); // Retornando respsota

    }


}
