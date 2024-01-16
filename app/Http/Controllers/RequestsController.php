<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestsRequest;
use Illuminate\Http\Request;
use App\Services\RequestsService;

class RequestsController extends Controller
{
    
    protected $requestsService;

    public function __construct(RequestsService $requestsService)
    {
        
        $this->requestsService = $requestsService;

    }

    public function search($id)
    {

        $query = $this->requestsService->getAll($id); // Metódo responsável por pegar todos os pedidos
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function registration(RequestsRequest $request)
    {

        $query = $this->requestsService->OrderRegistration($request); // Metódo responsável pelo cadastro de pedido
        return response()->json(['Response' => $query]); // Retornando resposta

    }

}
