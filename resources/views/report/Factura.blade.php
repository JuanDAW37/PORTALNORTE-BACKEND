<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura nº {{ $factura->numero }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1rem;
        }

        #cabecera {
            width: 100%;
            height: 10%;
            background: #999;
            color: white;
        }

        #concepto {
            text-align: center;
            font-weight: bolder;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: 0px;
            height: 50px;
            width: 100%;
            background: #999;
            color: white;
        }

        table {
            width: 100%;
            border: 1px solid black;
        }

        th,
        td {
            text-align: center;
            width: 25%;
        }
    </style>
</head>

<body>
    <div id="cabecera">
        <p>PORTALNORTE</p>
        <h2>FACTURA N°: {{ $factura->numero }}</h2>
        <h4>Fecha: {{date("d-m-Y", strtotime($factura->fecha))}}</h4>
    </div>
    <hr>
    <div>
        <b>Nombre y apellidos o Razón Social:</b> {{ $cliente }}
    </div>
    <div>
        <b>NIF:</b> {{ $nif }}
    </div>
    <div>
        <b>Dirección:</b> {{ $domicilio }}
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>CONCEPTO</th>
                </tr>
            </thead>
        </table>
        <div>
            <p>{{ $descripcion }}</p>
        </div>
    </div>
    <div id="footer">
        <table>
            <thead>
                <tr>
                    <th>Base</th>
                    <th>IVA</th>
                    <th>Cuota</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $factura->base }}</td>
                    <td>{{ $factura->iva }}</td>
                    <td>{{ $factura->cuota }}</td>
                    <td>{{ $factura->total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
