<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 0.5px solid #000;
            font-size: 10px;
            text-align: center;
            padding: 5px;
        }
        th {
            background-color: #ffffff;
        }
        .header{
            background-color: #dddddd;
        }
        .index{
            width: 10px;
        }
        .almacen-column {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <h3>{{ $title }}</h3>
    <table>
        <thead>
            <tr>
                <th class="index header">#</th>
                <th class="header">Código</th>
                <th class="header">Modelo</th>
                <th class="header almacen-column">{{ $almacen->descripcion }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @foreach ($productos as $producto)
                <tr>
                    <td class="index">{{ $count }}</td>
                    <td>{{ $producto->codigoProducto }}</td>
                    <td>{{ $producto->modelo }}</td>
                    <td class="almacen-column">
                        {{ $producto->Inventario->firstWhere('idAlmacen', $almacen->idAlmacen)->stock ?? 0 }}
                    </td>
                </tr>
                @php
                    $count++;
                @endphp
            @endforeach
            <tr>
                <td colspan="3" class="header">Total Stock</td>
                <td class="header almacen-column">{{ $totalStock }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>