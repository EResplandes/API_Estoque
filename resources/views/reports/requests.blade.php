@php
use \Carbon\Carbon;
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <style>
        /* Estilo adicional para centralizar a tabela */
        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>

</head>


<body>
    <div class="container">

        <div class="row">

            <div style="text-align: right">
                <p style="font-weight: 300;">Data de Emissão: {{ $dateTime }}</p>
            </div>

        </div>

        <div class="row">

            <div style="text-align: center;" class="col-12">
                <img src="https://www.gruporialma.com.br/wp-content/uploads/2024/02/wallpaper-Rialma.png" alt=""><br>
                <h3>RELATÓRIO DE PEDIDOS | {{ !empty($company) ? $company : 'GERAL' }} </h3>
                <br>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                @foreach($results as $result)
                <table style="border: 2px solid black; text-align: center; border-collapse: collapse; width: 100%" class="table">
                    <tbody>
                        <tr style="background-color: #666666; color: white;">
                            <td style="border: 1px solid black; padding: 2px;" colspan="11">PEDIDO Nº {{ $result['request_id'] }}</td>
                            <td colspan="1">Dt. Solic: {{ \Carbon\Carbon::parse($result['request_dt_opening'])->format('d/m/Y') }}</td>
                        </tr>
                        <thead">
                            <tr>
                                <th colspan="1" style="border: 1px solid black; padding: 2px; background-color: #f0f0f0;">Aplicação</th>
                                <td colspan="11" style="border: 1px solid black; padding: 2px">{{ $result['request_application'] }}</td>
                            </tr>
                            <tr style="background-color: #f0f0f2;">
                                <th colspan="6" style="border: 1px solid black; padding: 2px">Solicitante</th>
                                <th colspan="6" style="border: 1px solid black; padding: 2px">Status do Pedido</th>
                            </tr>
                            </thead>
                            <tr>
                                <td colspan="6" style="border: 1px solid black; padding: 2px">{{ $result['user_name'] }}</td>
                                <td colspan="6" style="border: 1px solid black; padding: 2px">{{ $result['order_status'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="12" style="border: 1px solid black; background-color: #f0f0f0;">
                                    <h5>ITENS DO PEDIDO</h5>
                                </td>
                            </tr>
                            <tr style="background-color: #f0f0f2;">
                                <th colspan="1" style="border: 1px solid black; padding: 2px">Qtd.</th>
                                <th colspan="3" style="border: 1px solid black; padding: 2px">Nome do Material</th>
                                <th colspan="5" style="border: 1px solid black; padding: 2px">Descrição do Material</th>
                                <th colspan="3" style="border: 1px solid black; padding: 2px">Dt. Vencimento</th>
                            </tr>
                            @foreach($result['materials'] as $material)
                            <tr>
                                <td colspan="1" style="border: 1px solid black; padding: 2px">{{ $material['material_amount'] }}</td>
                                <td colspan="3" style="border: 1px solid black; padding: 2px">{{ $material['material_name'] }}</td>
                                <td colspan="5" style="border: 1px solid black; padding: 2px">{{ $material['material_description'] }}</td>
                                <td colspan="3" style="border: 1px solid black; padding: 2px"> {{ \Carbon\Carbon::parse($material['material_dt_validity'])->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="12" style="background-color: black;"></td>
                            </tr>
                    </tbody>
                </table><br>
                @endforeach
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>



</html>