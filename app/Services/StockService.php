<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Jobs\SendRegistrationEmail;


class StockService
{

    public function getAll()
    {

        // Pegando todos os itens com suas devidas categorias
        $query = DB::table('stock')
            ->join('category', 'category.id', '=', 'stock.fk_category')
            ->join('companies', 'companies.id', '=', 'stock.fk_companie')
            ->select(
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

    public function getCategory()
    {

        $query = DB::table('category')->get(); // Pegando todas as categorias
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

        // Validação de imagem
        if ($imgProduct != '') {

            DB::table('stock')->insert($informations); // Registrando item no banco de dados
            return "Produto cadastrado com sucesso!"; // Retornando resposta

        } else {
            return "Ocorreu algum problema, entre em contato com o Administrador do sistema!"; // Retornando resposta
        }
    }

    public function filterProducts($request)
    {

        $filters = [
            'name' => $request->input('name'),
            'fk_category' => $request->input('fk_category'),
        ];

        $query = DB::table('stock')
            ->join('category', 'category.id', '=', 'stock.fk_category')
            ->join('companies', 'companies.id', '=', 'stock.fk_companie')
            ->select(
                'stock.name AS material_name',
                'stock.description',
                'stock.amount',
                'stock.dt_validity',
                'companies.name AS companie_name',
                'category.name AS category_name',
                'stock.image_directory'
            );

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
}
