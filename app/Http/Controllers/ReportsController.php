<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportsService;

class ReportsController extends Controller
{

    protected $reportsService;

    public function __construct(ReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
    }

    public function stock($id)
    {
        $query = $this->reportsService->stock($id); // Metódo responsável por gerar relatório de estoque
    }

    public function allStock()
    {
        $query = $this->reportsService->allStock(); // Metódo responsável por gerar relatório de estoque
    }

    public function requests(Request $request)
    {
        $query = $this->reportsService->requests($request); // Metódo responsável por gerar relatório de pedidos
    }

    public function transfers(Request $request)
    {
        $query = $this->reportsService->transfers($request); // Metódo responsável por gerar relatório de transferencias entre estoques
    }
}
