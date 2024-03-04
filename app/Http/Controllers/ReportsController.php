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

    public function stock($company = null, $category = null)
    {
        $query = $this->reportsService->stock($company, $category); // Metódo responsável por gerar relatório de estoque
    }

    public function requests($company = null, $user = null, $status = null, $initial_date = null, $end_date = null)
    {
        $query = $this->reportsService->requests($company, $user, $status, $initial_date, $end_date); // Metódo responsável por gerar relatório de pedidos
    }

    public function transfers(Request $request)
    {
        $query = $this->reportsService->transfers($request); // Metódo responsável por gerar relatório de transferencias entre estoques
    }
}
