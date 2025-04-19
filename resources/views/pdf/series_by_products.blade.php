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
        }
        th, td {
            border: 0.5px solid #000;
            font-size: 10px;
            text-align: center;
            padding: 5px;
            width: 100px; /* Ancho fijo para todas las celdas */
            height: 20px; /* Alto fijo para todas las celdas */
        }
        th {
            background-color: #f2f2f2;
        }
        
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <h3>{{ $producto->nombreProducto }}</h3>
    <h3 style="color: #555555">{{ $producto->modelo }}</h3>
    
    <table>
        <tbody>
            @foreach ($registros->chunk(4) as $chunk) <!-- Divide los registros en grupos de 4 -->
                <tr>
                    @foreach ($chunk as $reg)
                        <td>
                            <div>
                                <small>{{ $reg->numeroSerie }}</small>
                            </div>
                        </td>
                    @endforeach
                    <!-- Rellena celdas vacías si el último grupo tiene menos de 4 elementos -->
                    @for ($i = 0; $i < 4 - $chunk->count(); $i++)
                        <td></td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
    </table>
</body>
</html>