<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .recibo {
            width: 600px;
            border: 2px solid #000080;
            padding: 10px;
            margin: 35px auto;
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000080;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #000080;
            border: solid 1px #000080;
            border-radius: 8px;
        }

        .header .amounts {
            padding: 10px;
            font-size: 12px;
            text-align: right;
        }

        .amounts div {
            margin-bottom: 5px;
        }

        .info,
        .concept,
        .footer {
            margin-bottom: 15px;
        }

        .info div,
        .concept div,
        .footer div {
            margin-bottom: 5px;
        }

        .concept div,
        .footer div {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details table,
        .details th,
        .details td {
            border: 1px solid #000;
            text-align: center;
            font-size: 12px;
            padding: 5px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer div {
            width: 30%;
            text-align: center;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 12px;
        }

        .signature div {
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            width: 45%;
        }
    </style>
</head>

<body>

    <div class="recibo">
        <!-- Header -->
        <div class="header">
            <div class="title">
                <table style="margin-left:2%">
                    <tr>
                        <td style="width:10%">
                            <div>
                                <img src="{{ public_path('images/LogoRecibo.png') }}" alt="Logo" width="95%">
                            </div>
                        </td>
                        <td style="width: 40%;">
                            <div style="font-size : 24px; margin:1%;">
                                Sistema de agua potable <br>
                            </div>
                            <div style="font-size : 15px;">
                                Campiña "II" <br>
                                Quillacollo - Cochabamba - Bolivia
                            </div>
                        </td>
                        <td style="width: 30%; font-size : 15px; padding-left:2% ; margin-rigth:5%">
                            <div>
                                Pre-Aviso
                            </div>
                            <div style="color: rgb(182, 16, 16)">
                                Nro. Preaviso : #{{ $datos['recibo']->id }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="amounts">
                <table style="margin-left:15%">
                    <tr>
                        <td style="font-size: 15px; margin-rigth: 10px;">
                            Socio :
                            {{ $datos['socio']->nombre_socio . ' ' . $datos['socio']->primer_apellido_socio . ' ' . $datos['socio']->segundo_apellido_socio . '     ' . $datos['propiedad']->id }}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                            <div>Fecha de lectura : {{ $datos['recibo']->fecha_lectura }}</div>
                            Del mes : {{ $datos['consumo']->mes_correspondiente }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="details">
            <table>
                <thead>
                    <tr>
                        <th>Lectura Anterior : </th>
                        <th>Lectura Actual: </th>
                        <th>Metros Cubicos: </th>
                        <th>Consumo Basico : </th>
                        <th>Subtotal:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $datos['recibo']->lectura_anterior_correspondiente }}</td>
                        <td>{{ $datos['recibo']->lectura_actual_correspondiente }}</td>
                        <td>{{ $datos['consumo']->consumo_total }}</td>
                        </td>
                        <td></td>
                        <td>{{ $datos['recibo']->total }}</td>
                    </tr>
                    <tr>
                        <td>Multas por vencimiento</td>
                        <td colspan="3">
                            @if (!empty($datos['multas']))
                                @foreach ($datos['multas'] as $multa)
                                    {{ $multa->descripcion_infraccion }} - {{ $multa->monto_infraccion }} Bs. <br>
                                @endforeach
                            @else
                                No hay multas
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">Cancelar Nro. Cuenta : 3051 - 969273 &ensp; <br> Banco LA PROMOTORA EFV </td>
                        <td>Despues de la fecha de entrega.</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4">Total a pagar: </td>
                        <td>{{ $datos['recibo']->total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
