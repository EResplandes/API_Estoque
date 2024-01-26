<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class TransferService
{

    public function solicitation($request)
    {

        // 1º Passo -> Pegar Informações da requisição
        $infomations = [
            'fk_material' => $request->input('fk_material'),
            'fk_request' => $request->input('fk_request'),
            'requested_date' => Carbon::now('America/Sao_Paulo'),
            'fk_status' => 1,
        ];

        // 2ª Passo -> Pegar quantidade solicitada do material
        $amount = DB::table('application_materials')
            ->select('amount')
            ->where('fk_request', $infomations['fk_request'])
            ->where('fk_material', $infomations['fk_material'])
            ->get();

        // 3º Passo -> Inserindo a quantidade solicitado no array
        $infomations['quantity_request'] = $amount;


        dd($amount[0]->amount);

        // 4º Passo -> Inserir dados na tabela de transferências de material
        $insert = DB::table('material_transfer')->insert($infomations);

        // 5º Passo -> Validando inserção
        if ($insert) {
            return 'Transferência solicatada com sucesso!';
        } else {
            return 'Ocorreu algum problema, entre em contato com o administrador!';
        }
    }
}
