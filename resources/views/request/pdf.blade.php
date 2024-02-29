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
                <h2>Grupo Rialma S/A</h2>
                <h3>Pedido de Material Nº {{ $results[0]['request_id'] }}</h3>
                <br>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                @foreach($results as $result)

                <div class="row">
                    <table style="width: 100%;">
                        <tr class="col-12">
                            <td colspan=" 3">
                                <span style="font-weight: 900;">SOLICITANTE: </span><span style="padding: 5px; background-color: #f0f0f0">{{ $results[0]['user_name'] }}</span>
                            </td>
                            <td colspan="3">
                                <span style="font-weight: 900;">STATUS: </span><span style="padding: 5px; background-color: #f0f0f0">{{ $results[0]['order_status'] }}</span>
                            </td>
                            <td colspan="3">
                                <span style="font-weight: 900;">DT. SOLICITAÇÃO: </span><span style="padding: 5px; background-color: #f0f0f0">{{ \Carbon\Carbon::parse($result['request_dt_opening'])->format('d/m/Y') }}</span>
                            </td>
                        </tr><br>
                        <tr style="border: solid 2px black;">
                            <td colspan="12">
                                <span style="font-weight: 900;">APLICAÇÃO DO MATERIAL: </span>
                                <p style="border: 1px solid black; padding: 10px; background-color: #f0f0f0">{{ $results[0]['request_application'] }}</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <table style="text-align: center; border-collapse: collapse; width: 100%;" class="table">
                    <tbody">
                        <thead">
                            <td colspan="12" style="border: 1px solid black; background-color: #003360; color:white;">
                                <h4>ITENS DO PEDIDO</h4>
                            </td>
                            </tr>
                            <tr style="background-color: #f0f0f2; padding: 10px;">
                                <th colspan="1" style="border: 1px solid black; padding: 6px">Qtd.</th>
                                <th colspan="3" style="border: 1px solid black; padding: 6px">Nome do Material</th>
                                <th colspan="5" style="border: 1px solid black; padding: 6px">Descrição do Material</th>
                                <th colspan="3" style="border: 1px solid black; padding: 6px">Dt. Vencimento</th>
                            </tr>
                            @foreach($result['materials'] as $material)
                            <tr style="padding: 10px">
                                <td colspan="1" style="border: 1px solid black; padding: 2px">{{ $material['material_amount'] }}</td>
                                <td colspan="3" style="border: 1px solid black; padding: 2px">{{ $material['material_name'] }}</td>
                                <td colspan="5" style="border: 1px solid black; padding: 2px">{{ $material['material_description'] }}</td>
                                <td colspan="3" style="border: 1px solid black; padding: 2px"> {{ \Carbon\Carbon::parse($material['material_dt_validity'])->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                </table><br>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 70px;" class="row">
            <table style="width: 100%; text-align: center;">
                <tbody>
                    <tr>
                        <td colspan="4">
                            <hr style="width: 200px;">
                            <p style="font-weight: 900;">Assinatura do Almoxarife</p>
                        </td>
                        <td colspan="4">
                            <hr style="width: 200px;">
                            <p style="font-weight: 900;">Assinatura do Solicitante</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>



</html>