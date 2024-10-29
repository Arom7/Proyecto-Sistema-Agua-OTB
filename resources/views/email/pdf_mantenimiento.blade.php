@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Pagos-Deudas OTB Campiña II </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            color: #333;
        }

        .report-container {
            width: 95%;
            max-width: 900px;
        }

        h2 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sub-table td {
            border: 1px solid #333;
            /* Sin bordes dentro de las sub-filas */
            padding: 5px;
            font-size: 10px;
        }

        .center {
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>

<body>

    <div class="report-container">
        <div style="display: flex; align-items: center;">
            <table>
                <tr>
                    <td>
                        <img src="{{ public_path('images/Logo.png') }}" alt="Logo" width="75%" style=" margin: 0 12px">
                        <div class="center">
                            <p>Av.Blanco Galindo Km. 11 - Av. Oquendo
                                <br>
                                Quillacollo-Cochabamba-Bolivia
                                <br>
                            </p>
                        </div>
                    </td>
                    <td style="width: 80%;">
                        <h2>Reporte de Mantenimientos - OTB Campiña II <br> Sistema AquaCube</h2>
                        <div style="text-align: center; font-size : 15px;">
                            <p>Cada gota cuenta, consumela con responsabilidad.</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <tr>
                <th>Nro. Mantenimiento:</th>
                <th>Inicio del mantenimiento :</th>
                <th>Fin del mantenimiento :</th>
                <th>Responsable:</th>
                <th>Tipo de equipo :</th>
                <th>Proximo mantenimiento :</th>
                <th>Descripcion:</th>
                <th>Precio total : </th>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach ($mantenimientos as $mantenimiento)
                @php
                    $total += $mantenimiento->precio_total;
                @endphp
                <tr>
                    <td>{{ $mantenimiento->id }}</td>
                    <td>{{ Carbon::parse($mantenimiento->fecha_mantenimiento_inicio)->format('d/m/Y')}}</td>
                    <td>{{ Carbon::parse($mantenimiento->fecha_mantenimiento_fin)->format('d/m/Y')}}</td>
                    <td>{{ $mantenimiento->responsable }}</td>
                    <td>{{ $mantenimiento->tipo_equipo }}</td>
                    <td>{{ $mantenimiento->fecha_proximo_mantenimiento }}</td>
                    <td>{{ $mantenimiento->descripcion_mantenimiento }}</td>
                    <td>{{ $mantenimiento->precio_total }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" style="border: none;"></td>
                <td style="border: 1px solid #000;">Total : </td>
                <td style="border: 1px solid #000;">{{ $total }}</td>
            </tr>
        </table>
    </div>

</body>

</html>
