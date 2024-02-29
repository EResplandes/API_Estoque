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

    public function searchWarehouse($id)
    {

        $query = $this->requestsService->getWarehouse($id); // Metódo responsável por pegar todos os pedidos de acordo com a empresa
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function getProducts($id)
    {

        $query = $this->requestsService->getProducts($id); // Metódo que pega itens do pedido de acordo com numero do pedido
        return response()->json(['Response' => $query]); // Retornando resposta

    }

    public function pdf($id)
    {
        $query = $this->requestsService->pdf($id); // Metódo responsável por gerar o PDF do pedido de material
    }
}
