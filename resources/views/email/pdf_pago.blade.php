@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo A5</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .recibo-container {
            width: 95%;
            /* Ajusta para un ancho de hoja A5 */
            max-width: 500px;
            /* Limita a 500px para pantallas grandes */
            margin: 15px auto;
            padding: 15px;
            border: 1px solid #000080;
            border-radius: 8px;
        }

        .recibo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .recibo-title {
            font-size: 20px;
            color: #000080;
            font-weight: bold;
            text-transform: uppercase;
        }

        .recibo-header-datos {
            font-size: 15px;
            color: #000080;
        }

        .recibo-amounts {
            text-align: right;
            font-size: 15px;
        }

        .recibo-amounts div {
            margin-top: 4%;
            margin-bottom: 3px;
        }

        .date-box {
            display: flex;
            justify-content: space-between;
            width: 150px;
            margin: 10px 0;
            border: 1px solid #000;
            padding: 3px;
            font-size: 12px;
        }
        .date-box div {
            flex: 1;
            text-align: center;
            border-left: 1px solid #000;
        }
        .date-box div:first-child {
            border-left: none;
        }

        .info,
        .concept,
        .footer {
            margin-bottom: 8px;
        }

        .info div,
        .concept div {
            padding-top : 8px;
            padding-bottom: 8px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info table {
            margin: 15px auto; /* Centrar la tabla */
            border-collapse: collapse; /* Colapsar bordes */
            width: 80%; /* Ajustar el ancho de la tabla */
        }
        .info #columna {
            border: 1px solid black; /* Bordes de las celdas */
            padding: 8px; /* Espaciado interno */
            text-align: center; /* Centrar el texto */
        }
        .info th {
            border: 1px solid black; /* Bordes de las celdas */
            background-color: #f2f2f2; /* Color de fondo para encabezados */
        }

        .footer {
            display: flex;
            justify-content: flex-end; /* Alinea el footer a la derecha */
            gap: 10px;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .footer div {
            width: 30%;
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="recibo-container">
        <!-- Header -->
        <div class="recibo-header">
            <table>
                <tr>
                    <td style="width:18%" >
                        <div>
                            <img src="{{ public_path('images/LogoRecibo.png') }}" alt="Logo" width="90%">
                        </div>
                    </td>
                    <td>
                        <div class="recibo-title">RECIBO DE PAGO - Campiña II</div>
                        <div class="recibo-header-datos">
                            Quillacollo - Cochabamba - Bolivia
                        </div>
                        <div class="recibo-amounts">
                            <div>Fecha de pago : {{ Carbon::parse($datos['fecha_pago'])->format('d/m/Y') }} </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Information Section -->
        <div class="info">
            <div>
                Se cobro del siguiente mes: {{ Carbon::parse($datos['consumo']->mes_correspondiente)->format('m/Y') }} <br>

                <table>
                    <tr>
                        <th>Codigo de la propiedad : </th>
                        <th>Lectura Anterior : </th>
                        <th>Lectura Actual : </th>
                        <th>Consumo Total : </th>
                    </tr>

                    <tr>
                        <td id="columna">{{ $datos['consumo']->propiedad_id_consumo }}</td>
                        <td id="columna">{{ $datos['recibo']->lectura_anterior_correspondiente }}</td>
                        <td id="columna">{{ $datos['recibo']->lectura_actual_correspondiente }}</td>
                        <td id="columna">{{ $datos['consumo']->consumo_total}} </td>
                    </tr>
                </table>

                <div>
                    Al precio se le añadio cualquier otro concepto que se debia pagar ( Multas por inasistencia, ... ). Para una consulta mas detallada, por favor,
                    solicite su pre-aviso para mas informacion.
                </div>
            </div>
        </div>

        <div class="footer">
            <div>Total: {{$datos['recibo']->total}} Bs. </div>
        </div>

    </div>

</body>

</html>
