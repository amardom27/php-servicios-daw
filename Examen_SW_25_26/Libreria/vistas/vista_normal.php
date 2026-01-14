<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Libros</title>
    <style>
        .enlinea {
            display: inline
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            row-gap: 1rem;
        }

        .libro-cont {
            flex-basis: 33%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .libro-cont img {
            max-width: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <h1>Librería</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["lector"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
    <h2>Listado de libros</h2>
    <?php
    require "vistas/vista_libros.php";
    ?>
</body>

</html>