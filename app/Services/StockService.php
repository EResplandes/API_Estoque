<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StockService
{

    public function getAll()
    {

        // Pegando todos os itens com suas devidas categorias
        $query = DB::table('stock')
            ->join('category', 'category.id', '=', 'stock.fk_category')
            ->join('companies', 'companies.id', '=', 'stock.fk_companie')
            ->select(
                'stock.id AS id_stock',
                'stock.name AS material_name',
                'stock.description',
                'stock.amount',
                'stock.dt_validity',
                'companies.name AS companie_name',
                'category.name AS category_name',
                'stock.image_directory'
            )
            ->get(); // Pegando todos os produtos

        return $query; // Retornando resposta

    }

    public function getMy($id)
    {
        // Pegando todos os itens com suas devidas categorias
        $query = DB::table('stock')
            ->join('category', 'category.id', '=', 'stock.fk_category')
            ->join('companies', 'companies.id', '=', 'stock.fk_companie')
            ->select(
                'stock.id AS id_stock',
                'stock.name AS material_name',
                'stock.description',
                'stock.amount',
                'stock.dt_validity',
                'companies.name AS companie_name',
                'category.name AS category_name',
                'stock.image_directory'
            )
            ->where('companies.id', $id)
            ->get(); // Pegando todos os produtos

        return $query; // Retornando resposta
    }

    public function getCategory()
    {

        $query = DB::table('category')->select('id', 'name')->get(); // Pegando todas as categorias
        return $query; // Retornando resposta

    }

    public function registrationMaterial($request)
    {

        $directory = "/products"; // Criando diretório

        $imgProduct = $request->file('image_directory')->store($directory, 'public'); // Salvando imagem do produto

        // Salvando informações
        $informations = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'dt_validity' => $request->input('dt_validity'),
            'image_directory' => $imgProduct,
            'fk_companie' => $request->input('fk_companie'),
            'fk_category' => $request->input('fk_category'),
        ];

        // Validação de quantidade
        if ($informations['amount'] <= 0) {
            return 'A quantidade do produto não pode ser 0 ou um valor negativo!';
        }

        // Validação de imagem
        if ($imgProduct != '') {

            DB::table('stock')->insert($informations); // Registrando item no banco de dados
            return "Produto cadastrado com sucesso!"; // Retornando resposta

        } else {
            return "Ocorreu algum problema, entre em contato com o Administrador do sistema!"; // Retornando resposta
        }
    }

    public function filterProducts($request, $id)
    {

        $filters = [
            'name' => $request->input('name'),
            'fk_category' => $request->input('fk_category'),
        ];

        $query = DB::table('stock')
            ->join('category', 'category.id', '=', 'stock.fk_category')
            ->join('companies', 'companies.id', '=', 'stock.fk_companie')
            ->select(
                'stock.id AS id_stock',
                'stock.name AS material_name',
                'stock.description',
                'stock.amount',
                'stock.dt_validity',
                'companies.name AS companie_name',
                'category.name AS category_name',
                'stock.image_directory'
            )
            ->where('stock.fk_companie', $id);

        // Aplicar filtros
        if ($filters['name']) {
            $query->where('stock.name', 'LIKE', '%' . $filters['name'] . '%');
        }

        if ($filters['fk_category']) {
            $query->where('stock.fk_category', $filters['fk_category']);
        }


        // Executar a consulta e retornar os resultados
        $filteredProducts = $query->get();

        return $filteredProducts; // Retornando resposta

    }

    public function approvalRequest($request, $id)
    {

        // Pegando informações
        $informations = [
            'observations' => $request->input('observation'),
            'fk_status' => 2
        ];

        $query = DB::table('requests')->where('id', $id)->update($informations); // Query responsável por aprovar pedido

        // Pegando itens do pedido com suas respectivas quantidades
        $products = DB::table('application_materials')->where('fk_request', $id)->select(['fk_material', 'amount'])->get();

        // Subtraindo a quantidade aprovada do total disponível na tabela 'stock'
        foreach ($products as $product) {
            $productId = $product->fk_material;
            $approvedAmount = $product->amount;

            // Atualizando a tabela 'stock' subtraindo a quantidade aprovada
            DB::table('stock')
                ->where('id', $productId)
                ->decrement('amount', $approvedAmount);
        }

        // Verificando se ocorreu a atualização no banco
        if ($query) {
            return 'Pedido aprovado com sucesso!'; // Retornando resposta
        } else {
            return 'Ocorreu algum problema, entre em contato com o administrador do sistema!'; // Retornando resposta
        }
    }

    public function disapproveRequest($request, $id)
    {

        // Pegando informações
        $informations = [
            'observations' => $request->input('observation'),
            'fk_status' => 4
        ];

        $query = DB::table('requests')->where('id', $id)->update($informations); // Query responsável por reprovar pedido

        // Verificando se ocorreu a atualização no banco
        if ($query) {
            return 'Pedido reprovado com sucesso!'; // Retornando resposta
        } else {
            return 'Ocorreu algum problema, entre em contato com o administrador do sistema!'; // Retornando resposta
        }
    }
}
