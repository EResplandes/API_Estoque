<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Teste</th>
                <th>Teste</th>
            </tr>
        </thead>
        <tbody>
            @foreach($informations as $information)
            <tr>
                <td>{{ $information->material_name }}</td>
                <td>{{ $information->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>