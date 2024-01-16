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

    public function getAll($id)
    {

        $query = $this->stockService->getAll($id); // Pegando todos os itens do estoque
        return response()->json(['Response' => $query]); // Retornando resposta para a requisição

    }

    public function getCategory()
    {

        $query = $this->stockService->getCategory(); // Pegando todos as categorias
        return response()->json(['Response' => $query]);

    }

    public function registration(StockRequest $request)
    {

        $query = $this->stockService->registrationMaterial($request); // Cadastrando novo material
        return response()->json(['Response' => $query]); // Retornando resposta para a requisição

    }

    public function filter(Request $request)
    {

        $query = $this->stockService->filterProducts($request); // Filtrando produtos
        return response()->json(['Response' => $query]); // Retornando respsota para a requisição

    }

}
