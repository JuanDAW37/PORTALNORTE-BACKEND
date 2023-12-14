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
            height: 100px;
            background: #999;
            color: white;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: 0px;
            height: 40px;
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
        <p><h4>Fecha: {{date("d-m-Y", strtotime($factura->fecha))}}</h4></p>
    </div>
    <hr>
    <div>
        <p>Nombre y apellidos o Razón Social: {{ $cliente }}</p>
    </div>
    <div>
        <p>NIF {{ $nif }}</p>
    </div>
    <div>
        <p>Dirección: {{ $domicilio }}</p>
    </div>
    <div>
        <div>
            <div>
                <div>
                    <div>
                        <p>Concepto: </p>
                        <div>
                            <p>{{ $descripcion }}</p>
                        </div>
                    </div>
                </div>
            </div>
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
