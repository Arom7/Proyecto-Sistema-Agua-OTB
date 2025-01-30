<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; border: 1px solid #000; padding: 20px; }
        h2, h3 { text-align: center; }
        .info { margin-top: 20px; }
        .info p { font-size: 18px; }
        .highlight { background: #ddd; padding: 5px; }
        .instructions { margin-top: 20px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTB Campiña II - Distrito 5 - Quillacollo</h2>
        <h3>Cochabamba - Bolivia</h3>
        <p>¡Gracias por inscribirte al servicio de agua AQUA CUBE!</p>

        <div class="info">
            <p><strong>Usuario:</strong> <span class="highlight">{{ $data['username'] }}</span></p>
            <p><strong>Contraseña:</strong> <span class="highlight">{{ $data['password'] }}</span></p>
        </div>

        <div class="instructions">
            <h3>Para acceder a internet sigue estos pasos:</h3>
            <p>Paso 1: Conéctate a tu red WiFi</p>
            <p>Paso 2: Ingresa tu usuario y contraseña.</p>
            <p>Paso 3: ¡Listo! Empieza a navegar.</p>
            <p>Podras navegar en nuestra web visualizando las noticias
                sobre los eventos que se realizaran para nuestra OTB, entre estos
                las reuniones programadas, fechas de lecturacion, actividades...</p>
        </div>
    </div>
</body>
</html>
