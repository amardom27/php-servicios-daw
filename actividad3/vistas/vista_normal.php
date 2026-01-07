<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad 3</title>
</head>

<body>
    <h1>Actividad 3</h1>
    <form action="index.php" method="post">
        <p>
            Bienvenido <strong><?= $usuario["nombre"] ?></strong> -
            <button type="submit" name="btnSalirSesion">Salir</button>
        </p>
    </form>
</body>

</html>