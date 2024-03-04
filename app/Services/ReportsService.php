<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use DateTime;
use DateTimeZone;

class ReportsService
{

    public function stock($company, $category)
    {

        // 1º Passo -> Pegando Data e Hora que foi Gerado o PDF
        $dateTime = $this->date();

        // 2º Passo -> Buscar nome da empresa
        $companie = [];
        if (!empty($company)) {
            $companie = DB::table('companies')
                ->select('name')
                ->where('id', $company)
                ->get();
        }

        // 3º Passo -> Pegando todos os itens com suas devidas categorias
        $informations = DB::table('stock')
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
            );

        // Verifica se existe a company definida
        if ($company) {
            $informations = $informations->where('fk_companie', $company);
        }

        // Verifica se existe a company definida
        if ($category) {
            $informations = $informations->where('fk_category', $category);
        }

        $informations = $informations->get(); // Pegando informações

        // 4º Passo -> Gerando PDF
        $pdf = new Dompdf();
        $pdf->loadHtml(view('reports.stock', ['informations' => $informations, 'companie' => $companie, 'dateTime' => $dateTime]));
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return $pdf->stream('relatorio_estoque.pdf');
    }

    public function requests($company, $user, $status, $initial_date, $end_date)
    {

        // 1º Passo -> Pegando Data e Hora que foi Gerado o PDF
        $dateTime = $this->date();

        // 2º Passo -> Buscar nome da empresa
        $companie = "";
        if ($company) {
            $companie = DB::table('companies')
                ->select('name')
                ->where('id', $company)
                ->get();

            $companie = $companie[0]->name;
        }

        // 3º Passo -> Buscasr todos pedidos
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
                'requests.fk_status',
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
                'requests.fk_status',
                'stock.name as material_name',
                'stock.description as material_description',
                'application_materials.amount as material_amount',
                'stock.dt_validity as material_dt_validity',
                'stock.image_directory as material_image_directory',
                'users.name as user_name',
                'order_status.status as order_status'
            )
            ->orderByDesc('requests.id');

        // Verifica se existe a company definida
        if ($company) {
            $query = $query->where('companies.id', $company); // Adiciona a condição para filtrar por id da empresa
        }

        // Verifica se existe a initial_date e end_date definida
        if ($initial_date && $end_date) {
            $query = $query->whereBetween('requests.dt_opening', [$initial_date, $end_date]);
        }

        // Verifica se existe a user definida
        if ($user) {
            $query = $query->where('users.id', $user);
        }

        // Verifica se existe a user definida
        if ($status) {
            $query = $query->where('requests.fk_status', $status);
        }

        // Executando query
        $query = $query->get();

        // Agrupa os materiais por pedido no resultado
        $results = $query->groupBy('request_id')->map(function ($group) {
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
        // Gerar PDF
        $pdf = new Dompdf();
        $pdf->loadHtml(view('reports.requests', ['results' => $results, 'dateTime' => $dateTime, 'company' => $companie]));
        $pdf->setPaper('A4');
        $pdf->render();

        return $pdf->stream('meu_pdf.pdf');
    }

    public function transfers($request)
    {
        dd($request);
    }

    public function date()
    {
        // 1º Passo -> Pegando Data e Hora que foi Gerado o PDF
        $dataAtual = date('d/m/Y', strtotime('today'));
        $timezone = new DateTimeZone('America/Sao_Paulo'); // Configura o fuso horário para Brasília
        $date = new DateTime('now', $timezone); // Obtém a data e hora atual considerando o fuso horário
        $dateTime = $date->format('d/m/Y H:i:s');

        return $dateTime;
    }
}
