<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransferService;

class TransferController extends Controller
{

    protected $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    public function solicitation(Request $request)
    {

        $query = $this->transferService->solicitation($request); // Registrando solicitação de transferência de estoque
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function approval($id)
    {

        $query = $this->transferService->approval($id); // Aprovando solitação de transferência de material
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function disapproval(Request $request, $id)
    {

        $query = $this->transferService->disapproval($request, $id); // Reprovando a solicitação de transferência de material
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function mysolicitations($id)
    {

        $query = $this->transferService->mysolicitations($id); // Pegando todas minhas solicitações
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function requestForMe($id)
    {

        $query = $this->transferService->requestForMe($id); // Pegando todas solicitações para meu estoque
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function checkRequest($id)
    {
        $query = $this->transferService->checkRequest($id); // Verificando se já existe solicitação
        return response()->json(['Response' => $query]); // Retornando resposta
    }
}
