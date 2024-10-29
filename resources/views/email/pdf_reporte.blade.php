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
                        <h2>Reportes
                            @if ($datosTitle['tipo_reporte'] == 1)
                                de Pagos
                                @else
                                de Deudas
                            @endif
                            - OTB Campiña II - Sistema AquaCube</h2>
                        <div style="text-align: center; font-size : 15px;">
                            <p>Cada gota cuenta, consumela con responsabilidad.</p>
                            <p>
                                Reporte de las fechas : {{Carbon::parse($datosTitle['fecha_inicio'])->format('d/m/Y')}} - {{Carbon::parse($datosTitle['fecha_fin'])->format('d/m/Y')}}.
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

        </div>



        <table>
            <tr>
                <th>Nombre del Socio : </th>
                <th>Detalles : </th>
            </tr>
            @foreach ($socios as $socio)
                <tr>
                    <td>
                        {{ $socio->nombre_socio }} {{ $socio->primer_apellido_socio }}
                        {{ $socio->segundo_apellido_socio }}
                    </td>
                    <td>
                        @if ($socio->propiedades->isNotEmpty())
                            <table class="sub-table">
                                <tr>
                                    <th>Propiedad:</th>
                                    <th>Lista de Recibos</th>
                                </tr>
                                @foreach ($socio->propiedades as $propiedad)
                                    <tr>
                                        <td style="text-align:center;">{{ $propiedad->id }}</td>
                                        <td style="width: 85%;">
                                            @if ($propiedad->recibos->isNotEmpty())
                                                <table>
                                                    @foreach ($propiedad->recibos as $recibo)
                                                        <tr>
                                                            <td>
                                                               Nro. Recibo :<br> {{ $recibo->id }}
                                                            </td>
                                                            <td>
                                                               Fecha lectura : <br> {{ $recibo->fecha_lectura }}
                                                            </td>
                                                            <td>
                                                                Metros cubicos: <br> {{ $recibo->consumo }}
                                                            </td>
                                                            <td>
                                                                Por el mes : <br> {{ Carbon::parse($recibo->mes)->format('m/Y') }}
                                                            </td>
                                                            <td>
                                                                Subtotal:<br> {{ $recibo->total }}
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4">Total a pagar : </td>
                                                        <td>{{ $propiedad->recibos->sum('total') }}</td>
                                                    </tr>
                                                </table>
                                            @else
                                                <p>No se tienen recibos registrados.</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>No se tienen propiedades registradas.</p>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

</body>

</html>
