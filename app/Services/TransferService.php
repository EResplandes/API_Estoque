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
            'fk_request' => $request->input('fk_request'),
            'requested_date' => Carbon::now('America/Sao_Paulo'),
            'fk_status' => 1,
        ];

        // Obter array de IDs de materiais
        $fk_materials = $request->input('fk_material');

        foreach ($fk_materials as $fk_material) {
            // 2ª Passo -> Pegar quantidade solicitada do material
            $amount = DB::table('application_materials')
                ->select('amount')
                ->where('fk_request', $infomations['fk_request'])
                ->where('fk_material', $fk_material['id'])
                ->first();

            if ($amount) {

                // 3º Passo -> Inserir dados na tabela de transferências de material
                $infomations['fk_material'] = $fk_material['id'];
                $infomations['quantity_request'] = $amount->amount;

                $insert = DB::table('material_transfer')->insert($infomations);

                // Verificar se a inserção foi bem-sucedida para cada material
                if (!$insert) {
                    return 'Ocorreu algum problema ao inserir a transferência de material para o ID: ' . $fk_material['id'];
                }
            } else {
                return 'A quantidade solicitada para o material com o ID ' . $fk_material['id'] . ' não foi encontrada.';
            }
        }

        // 5º Passo -> Resposta para a requisição
        return 'Transferências solicitadas com sucesso!';
    }

    public function approval($id, $fk_companie)
    {
        DB::beginTransaction();

        try {

            // 1º Passo -> Alterar o status da solicitação para APROVADO e Inserir data da aprovação
            $query = DB::table('material_transfer')
                ->where('id', $id)
                ->update(['fk_status' => 3, 'response_date' => Carbon::now('America/Sao_Paulo')]);

            // 2º Passo -> Pegar id do material para retirar do estoque e pegar quantidade solicitada
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

            // 4º Passo -> Pegar informações do material para serem inseridos
            if ($removeItens) {
                $material = DB::table('stock')
                    ->where('id', $informations[0]->fk_material)
                    ->get();
            }

            // 5º Passo -> Pegar id do pedido
            $idRequest = DB::table('material_transfer')
                ->select('fk_request')
                ->where('id', $id)
                ->get();

            // 6º Passo -> Pegar id do usuário que fez a solicitação
            $fk_solicitation = DB::table('requests')
                ->where('requests.id', $idRequest[0]->fk_request)
                ->select('fk_user')
                ->first();

            // 7º Passo -> Pegar id da compania desse usuário
            $fk_companie_solicitation = DB::table('users')
                ->where('id', $fk_solicitation->fk_user)
                ->select('fk_companie')
                ->first();

            // 8º Passo -> Montar array com informaçoes a serem inseridas
            if ($material) {
                $arrayData = [
                    'name' => $material[0]->name,
                    'description' => $material[0]->description,
                    'amount' => $informations[0]->quantity_request,
                    'dt_validity' => $material[0]->dt_validity,
                    'fk_companie' => $fk_companie_solicitation->fk_companie,
                    'fk_category' => $material[0]->fk_category,
                    'image_directory' => $material[0]->image_directory
                ];
            }

            // 9ª Passo -> Inserir no estoque do solicitante
            if ($arrayData) {
                $queryInsert = DB::table('stock')->where('fk_companie', $fk_companie_solicitation->fk_companie)->insertGetId($arrayData);
            }

            // 10º Passo -> Pegar id do material e id do pedido
            if ($queryInsert) {
                $request = DB::table('material_transfer')
                    ->select('fk_request', 'fk_material')
                    ->where('id', $id)
                    ->get(); // Pegando número do pedido e o material
            }

            // 11ª Passo -> Remover item do pedido
            if ($request) {
                $removeItem = DB::table('application_materials')
                    ->where('fk_request', $request[0]->fk_request)
                    ->where('fk_material', $request[0]->fk_material)
                    ->delete(); // Deleta item do pedido
            }

            // 12ª Passo -> Adicionar item no pedido 
            if ($removeItem) {
                $insertItem = DB::table('application_materials')
                    ->insert([
                        'fk_request' => $request[0]->fk_request,
                        'fk_material' => $queryInsert,
                        'amount' => $informations[0]->quantity_request
                    ]); // Inseriondo item no pedido
            }

            // 13º Passo -> Salvando alterações
            if ($insertItem) {
                DB::commit();
            }

            // 14º Passo -> Retornando resposta
            return 'Transferência de material aprovada com sucesso!';
        } catch (\Exception $e) {

            // 1ª Passo -> Desafendo todas as alterações
            DB::rollBack();

            // 2º Passo -> Retornando resposta para o usuário
            return 'Ocorreu algum problema, entre em contato com o administrador!';
        }
    }

    public function confirmationReceipt($id)
    {

        // 1ª Passo -> Alterar o status para 4 - RECEBIDO
        $query = DB::table('material_transfer')->where('id', $id)->update(['status' => 4]);

        // 2ª Passo -> Pegar dados do item da solicitação
        dd('teste');

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
            ->where('fk_status', 1)
            ->join('stock', 'stock.id', '=', 'material_transfer.fk_material')
            ->join('transfer_status', 'transfer_status.id', '=', 'material_transfer.fk_status')
            ->select('material_transfer.id', 'material_transfer.quantity_request', 'material_transfer.requested_date', 'material_transfer.fk_status', 'stock.name',  'stock.description', 'stock.description', 'transfer_status.status')
            ->get()
            ->map(function ($item) {
                return collect($item)->except(['created_at', 'updated_at', 'fk_category'])->toArray();
            });

        // 3º Passo -> Resposta para a requisição com todas as solicitaçõess
        return response()->json(['Response' => $requestsforMe]);
    }

    public function checkRequest($id)
    {

        // 1º Passo -> Verificar se tem alguma solicitação de transferencia desse pedido
        $query = DB::table('material_transfer')->where('fk_request', $id)->where('fk_status', 1)->exists();

        // 2ª Passo -> Retornar resposta
        if ($query) {
            return 'A solicitação já foi realizada!';
        } else {
            return 'A solicitação não foi realizada!';
        }
    }
}
