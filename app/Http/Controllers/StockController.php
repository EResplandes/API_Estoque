<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StockService;
use App\Http\Requests\StockRequest;

class StockController extends Controller
{

    protected $stockService;

    public function __construct(StockService $stockService)
    {

        $this->stockService = $stockService;
    }

    public function getAll()
    {

        $query = $this->stockService->getAll(); // Pegando todos os itens do estoque
        return response()->json(['Response' => $query]); // Retornando resposta para requisição

    }

    public function getMy($id)
    {

        $query = $this->stockService->getMy($id); // Pegando todos os itens do estoque de um certa empresa
        return response()->json(['Response' => $query]); // Retornando resposta para requisição

    }

    public function getCategory()
    {

        $query = $this->stockService->getCategory(); // Pegando todos as categorias
        return response()->json(['Response' => $query]);
    }

    public function registration(StockRequest $request)
    {

        $query = $this->stockService->registrationMaterial($request); // Cadastrando novo material
        return response()->json(['Response' => $query]); // Retornando resposta para requisição

    }

    public function filter(Request $request, $id)
    {

        $query = $this->stockService->filterProducts($request, $id); // Filtrando produtos
        return response()->json(['Response' => $query]); // Retornando resposta para requisição

    }

    public function approval(Request $request, $id)
    {

        $query = $this->stockService->approvalRequest($request, $id); // Metódo responsável por aprovar pedido
        return response()->json(['Response' => $query]); // Retornando a resposta para a requisição

    }

    public function disapprove(Request $request, $id)
    {

        $query = $this->stockService->disapproveRequest($request, $id); // Metódo responsável por reporvar pedido
        return response()->json(['Response' => $query]); // Retornando resposta para requisição


    }
}
