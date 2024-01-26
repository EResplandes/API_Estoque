<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RequestsService
{

    public function getAll($id)
    {
        $query = DB::table('requests')
            ->leftJoin('application_materials', 'requests.id', '=', 'application_materials.fk_request')
            ->leftJoin('stock', 'stock.id', '=', 'application_materials.fk_material')
            ->join('users', 'users.id', '=', 'requests.fk_user')
            ->join('order_status', 'order_status.id', '=', 'requests.fk_status')
            ->groupBy(
                'requests.id',
                'requests.dt_opening',
                'requests.observations',
                'requests.application',
                'stock.name',
                'stock.description',
                'application_materials.amount',
                'stock.dt_validity',
                'stock.image_directory',
                'users.name',
                'order_status.status'
            )
            ->select(
                'requests.id as request_id',
                'requests.dt_opening as request_dt_opening',
                'requests.observations as request_observation',
                'requests.application as request_application',
                'stock.name as material_name',
                'stock.description as material_description',
                'application_materials.amount as material_amount',
                'stock.dt_validity as material_dt_validity',
                'stock.image_directory as material_image_directory',
                'users.name as user_name',
                'order_status.status as order_status'
            )
            ->where('requests.fk_user', $id)
            ->get();

        // Agrupa os materiais por pedido no resultado
        $result = $query->groupBy('request_id')->map(function ($group) {
            return [
                'request_id' => $group[0]->request_id,
                'request_dt_opening' => $group[0]->request_dt_opening,
                'request_observation' => $group[0]->request_observation,
                'request_application' => $group[0]->request_application,
                'user_name' => $group[0]->user_name,
                'order_status' => $group[0]->order_status,
                'materials' => $group->map(function ($item) {
                    return [
                        'material_name' => $item->material_name,
                        'material_description' => $item->material_description,
                        'material_amount' => $item->material_amount,
                        'material_dt_validity' => $item->material_dt_validity,
                        'material_image_directory' => $item->material_image_directory,
                    ];
                })->toArray(),
            ];
        })->values()->toArray();

        return $result;
    }

    public function OrderRegistration($request)
    {

        // Pegando informações
        $informations = [
            'dt_opening' => Carbon::now('America/Sao_Paulo'),
            'fk_status' => 1,
            'fk_user' => $request->input('fk_user'),
            'application' => $request->input('application'),
        ];

        $cartArray = $request->input('cart'); // Pegando itens do carrinho

        // Verificando se carrinho está vazio
        if (empty($cartArray)) {
            return 'Carrinho está vazio, selecione ao mínimo 1 item!';
        }

        $errors = [];

        // Validar campos vazios
        foreach ($informations as $key => $value) {
            if (empty($value)) {
                $errors[] = "O campo '$key' não pode estar vazio.";
            }
        }

        // Se houver erros, retornar as mensagens de erro
        if (!empty($errors)) {
            return $errors;
        }

        $id = DB::table('requests')->insertGetId($informations); // Registra novo pedido

        // Verifica se a decodificação foi bem-sucedida
        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Erro ao decodificar JSON');
        }

        // Insere os itens na tabela application_materials
        if ($id != null || $id != '') {
            foreach ($cartArray as $item) {
                DB::table('application_materials')->insert([
                    'fk_request' => $id,
                    'fk_material' => $item['id'],
                    'amount' => $item['amount'],
                ]);
            }
            return 'Pedido cadastrado com sucesso!';
        } else {
            return 'Occoreu algum problema, tente mais tarde!';
        }
    }

    public function getWarehouse($id)
    {

        $query = DB::table('requests')
            ->leftJoin('application_materials', 'requests.id', '=', 'application_materials.fk_request')
            ->leftJoin('stock', 'stock.id', '=', 'application_materials.fk_material')
            ->join('users', 'users.id', '=', 'requests.fk_user')
            ->join('order_status', 'order_status.id', '=', 'requests.fk_status')
            ->join('companies', 'companies.id', '=', 'users.fk_companie') // Relacionamento entre companies e users
            ->groupBy(
                'requests.id',
                'requests.dt_opening',
                'requests.observations',
                'requests.application',
                'stock.name',
                'stock.description',
                'application_materials.amount',
                'stock.dt_validity',
                'stock.image_directory',
                'users.name',
                'order_status.status'
            )
            ->select(
                'requests.id as request_id',
                'requests.dt_opening as request_dt_opening',
                'requests.observations as request_observation',
                'requests.application as request_application',
                'stock.name as material_name',
                'stock.description as material_description',
                'application_materials.amount as material_amount',
                'stock.dt_validity as material_dt_validity',
                'stock.image_directory as material_image_directory',
                'users.name as user_name',
                'order_status.status as order_status'
            )
            ->where('companies.id', $id) // Adiciona a condição para filtrar por id da empresa
            ->orderBy('requests.id', 'DESC')
            ->get();

        // Agrupa os materiais por pedido no resultado
        $result = $query->groupBy('request_id')->map(function ($group) {
            return [
                'request_id' => $group[0]->request_id,
                'request_dt_opening' => $group[0]->request_dt_opening,
                'request_observation' => $group[0]->request_observation,
                'request_application' => $group[0]->request_application,
                'user_name' => $group[0]->user_name,
                'order_status' => $group[0]->order_status,
                'materials' => $group->map(function ($item) {
                    return [
                        'material_name' => $item->material_name,
                        'material_description' => $item->material_description,
                        'material_amount' => $item->material_amount,
                        'material_dt_validity' => $item->material_dt_validity,
                        'material_image_directory' => $item->material_image_directory,
                    ];
                })->toArray(),
            ];
        })->values()->toArray();

        return $result;
    }

    public function getProducts($id)
    {

        $query = DB::table('application_materials')
            ->join('stock', 'stock.id', '=', 'application_materials.fk_material')
            ->join('companies', 'companies.id', '=', 'stock.fk_companie')
            ->select(
                'application_materials.id AS id_application',
                'stock.id AS id_material',
                'application_materials.amount AS quantity_application',
                'application_materials.status AS status_application',
                'stock.name',
                'stock.description',
                'stock.amount AS quantity_stock',
                'stock.dt_validity',
                'stock.fk_companie',
                'companies.name AS stock_origin'
            )
            ->where('application_materials.fk_request', $id)
            ->get();

        return $query;
    }
}
