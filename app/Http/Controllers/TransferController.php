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


        dd('teste');
    }
}
