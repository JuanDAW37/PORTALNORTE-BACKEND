<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmación de Reserva</title>
    <style>
        *{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1rem;
        }
        .cabecera{
            background:grey;
            color: white;
            text-align: center;
        }
        .saludo{
            background:grey;
            color: white;
        }
    </style>
</head>
<body>
    <div class="saludo">Estimado: {{ $cliente }}</div>
    <div class="saludo">Confirme sus datos:</div>
    <div class="saludo">NIF: {{ $nif }}</div>
    <div class="saludo">Domicilio: {{ $domicilio }}</div>
    <h2 class="cabecera">Le indicamos la información de su reserva</h2>
    <p><b>Número de reserva:</b>&nbsp;{{ $reserva->numero }}</p>
    <p><b>Fecha y hora de la reserva:</b>&nbsp;{{date("d-m-Y", strtotime($reserva->fecha))}} a las {{ $reserva->hora }}</p>
    <p><b>Tipo de servicio:</b>&nbsp;{{ $descripcion }}
</body>
</html>
