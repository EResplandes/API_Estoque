<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    
    protected $userService;

    public function __construct(UserService $userService)
    {   
        $this->userService = $userService;
    }

    public function getAll()
    {

        $query = $this->userService->getAll(); // Query responsável por buscar todos os usuários
        return response()->json(['Response' => $query]); // Retornando respsota para a requisição

    }

    public function registration(UserRequest $request)
    {

        $query = $this->userService->registrationUser($request); // Query responsável por cadastrar o usuário
        return response()->json(['Response' => 'Usuário cadastro com sucesso!']);

    }

    public function deactivation($id)
    {

        $query = $this->userService->deactivationUser($id); // Query responsável por desativar usuário
        return response()->json(['Response' => 'Usuário desativado com sucesso!']);
        
    }

    public function activate($id)
    {

        $query = $this->userService->activateUser($id); // Query responsável por ativar usuário
        return response()->json(['Response' => 'Usuário ativado com sucesso!']);

    }

} 
