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
        $infomations['quantity_request'] = $amount[0]->amount;

        // 4º Passo -> Inserir dados na tabela de transferências de material
        $insert = DB::table('material_transfer')->insert($infomations);

        // 5º Passo -> Resposta para a requisição
        if ($insert) {
            return 'Transferência solicatada com sucesso!';
        } else {
            return 'Ocorreu algum problema, entre em contato com o administrador!';
        }
    }

    public function approval($id)
    {

        // 1º Passo -> Alterar o status da solicitação para APROVADO e Inserir data da aprovação
        $query = DB::table('material_transfer')
            ->where('id', $id)
            ->update(['fk_status' => 3, 'response_date' => Carbon::now('America/Sao_Paulo')]);

        // 2º Passo -> Pegar id do material para retirar do estoque e pegar qunatidade solicitada
        $informations = DB::table('material_transfer')
            ->select('fk_material', 'quantity_request')
            ->where('id', $id)
            ->get();

        // 3º Passo -> Retirar itens do estoque
        if ($query) {
            $removeItens = DB::table('stock')
                ->where('id', $informations[0]->fk_material)
                ->decrement('amount', $informations[0]->quantity_request);
        }

        // 4º Passo -> Resposta para a requisição
        if ($removeItens) {
            return 'Transferência de material aprovada com sucesso!';
        } else {
            return 'Ocorreu algum problema, entre em contato com o administrador!';
        }
    }

    public function confirmationReceipt($id)
    {

        // 1ª Passo -> Alterar o status para 4 - RECEBIDO
        $query = DB::table('material_transfer')->where('id', $id)->update(['status' => 4]);

        // 2ª Passo -> Pegar dados do item da solicitação

        // 3ª Passo -> Cadastrar item no meu estoque

        // 4º Passo -> Alterar id do material no pedido para o id produto que foi transferido para meu estoque

        // 5º Passo -> Resposta para a requisição

    }

    public function disapproval($request, $id)
    {

        // 1º Passo -> Pegar a justificativa da reprovação
        $observation = $request->input('observation');

        // 2º Passo -> Alterar status da solicitação para REPROVADO, adicionar a observação e inserir data
        $query = DB::table('material_transfer')
            ->where('id', $id)
            ->update(['observation' => $observation, 'fk_status' => 5, 'response_date' => Carbon::now('America/Sao_Paulo')]);

        // 3º Passo -> Reposta para a requisiçãos
        if ($query) {
            return 'Transferência de material reprovada com sucesso!';
        } else {
            return 'Ocorreu algum problema, entre em contato com o administrador!';
        }
    }

    public function mysolicitations($id)
    {

        // 1º Passo -> Pegar todos os pedidos de acordo com o id do usuários
        $requests = DB::table('requests')
            ->where('fk_user', $id)
            ->pluck('id');

        // 2ª Passo -> Buscar de acordo com id dos pedidos de cada usuário se tem alguma solicitação de transferencia de material relacionada aquele pedido
        $mySolicitations = DB::table('material_transfer')
            ->whereIn('fk_request', $requests)
            ->join('stock', 'stock.id', '=', 'material_transfer.fk_material')
            ->select('material_transfer.*', 'stock.*')
            ->get()
            ->map(function ($item) {
                return collect($item)->except(['created_at', 'updated_at', 'fk_category'])->toArray();
            });

        // 3º Passo -> Resposta para a requisição com todos as solicitações
        return $mySolicitations;
    }

    public function requestForMe($id)
    {

        // 1º Passo -> Pegar todas as solicitações feitas para meu estoque com base no id dos produtos vinculados ao meu estoque
        $myStock = DB::table('stock')
            ->where('fk_companie', $id)
            ->pluck('id');

        // 2ª Passo -> Verificar se tem algum desses ID na tabela de solicitação de transferencia
        $requestsforMe = DB::table('material_transfer')
            ->whereIn('fk_material', $myStock)
            ->join('stock', 'stock.id', '=', 'material_transfer.fk_material')
            ->select('material_transfer.*', 'stock.*')
            ->get()
            ->map(function ($item) {
                return collect($item)->except(['created_at', 'updated_at', 'fk_category'])->toArray();
            });

        // 3º Passo -> Resposta para a requisição com todas as solicitações
        return response()->json(['Response' => $requestsforMe]);
    }
}
