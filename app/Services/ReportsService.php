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

    public function stock($id)
    {

        // 1º Passo -> Pegando Data e Hora de foi Gerado o PDF
        $dataAtual = date('d/m/Y', strtotime('today'));
        $timezone = new DateTimeZone('America/Sao_Paulo'); // Configura o fuso horário para Brasília
        $date = new DateTime('now', $timezone); // Obtém a data e hora atual considerando o fuso horário
        $dateTime = $date->format('d/m/Y H:i:s');

        // 1º Passo -> Buscar nome da empresa
        $companie = DB::table('companies')
            ->select('name')
            ->where('id', $id)
            ->get();

        // 2º Passo -> Pegando todos os itens com suas devidas categorias
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
            )
            ->where('stock.fk_companie', $id)
            ->get(); // Pegando todos os produtos

        // 3º Passo -> Gerando PDF
        $pdf = new Dompdf();
        $pdf->loadHtml(view('reports.stock', ['informations' => $informations, 'companie' => $companie[0]->name, 'dateTime' => $dateTime]));
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return $pdf->stream('meu_pdf.pdf');
    }

    public function allStock()
    {

        // 1º Passo -> Pegando Data e Hora de foi Gerado o PDF
        $dataAtual = date('d/m/Y', strtotime('today'));
        $timezone = new DateTimeZone('America/Sao_Paulo'); // Configura o fuso horário para Brasília
        $date = new DateTime('now', $timezone); // Obtém a data e hora atual considerando o fuso horário
        $dateTime = $date->format('d/m/Y H:i:s');


        // Pegando todos os itens com suas devidas categorias
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
            )
            ->orderByDesc('companie_name')
            ->get(); // Pegando todos os produtos


        // Gerando PDF
        $pdf = new Dompdf();
        $pdf->loadHtml(view('reports.stock', ['informations' => $informations, 'dateTime' => $dateTime]));
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return $pdf->stream('meu_pdf.pdf');
    }

    public function requests($request)
    {
        dd($request);
    }

    public function transfers($request)
    {
        dd($request);
    }
}
