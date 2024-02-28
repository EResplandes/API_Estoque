<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>


<body>
    <div style="text-align: right;">
    </div>
    <div class="container">

        <div class="row">
            <div style="text-align: right">
                <p style="font-weight: 300;">Data de Emissão: {{ $dateTime }}</p>
            </div>
        </div>

        <div class="row">

            <div style="text-align: center;" class="col-12">
                <img src="../../../resources/img/logo.png">
                <h3>RELATÓRIO DE ESTOQUE | {{ !empty($companie) ? $companie : 'GERAL' }} </h3>
                <br>
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <table style="border: 2px solid black; text-align: center; border-collapse: collapse;" class="table">
                    <thead>
                        <tr>
                            <th style=" border: 1px solid black; padding: 10px" scope="col-1">ID</th>
                            <th style=" border: 1px solid black; padding: 10px;" scope="col-2">MATERIAL</th>
                            <th style=" border: 1px solid black; padding: 10px;" scope="col-2">DESCRIÇÃO</th>
                            <th style=" border: 1px solid black; padding: 10px;" scope="col-2">QUANTIDADE</th>
                            <th style=" border: 1px solid black; padding: 10px;" scope="col-2">CATEGORIA</th>
                            <th style=" border: 1px solid black; padding: 10px;" scope="col-2">ARMAZÉM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($informations as $information)
                        <tr>
                            <td style=" border: 1px solid black; padding: 10px">{{ $information->id_stock }}</td>
                            <td style=" border: 1px solid black; padding: 10px">{{ $information->material_name }}</td>
                            <td style=" border: 1px solid black; padding: 10px">{{ $information->description }}</td>
                            <td style=" border: 1px solid black; padding: 10px">{{ $information->amount }}</td>
                            <td style=" border: 1px solid black; padding: 10px">{{ $information->category_name }}</td>
                            <td style=" border: 1px solid black; padding: 10px">{{ $information->companie_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>



</html>