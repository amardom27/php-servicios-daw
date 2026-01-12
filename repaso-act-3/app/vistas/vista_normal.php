<?php
$url = API_TIENDA . "/productos";

$respuesta = consumir_servicios_REST($url, "GET");
$json_productos = json_decode($respuesta, true);

if (!$json_productos) {
    session_destroy();
    die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($json_productos["error"])) {
    session_destroy();
    die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_productos["error"] . "</p>"));
}

if (isset($json_productos["productos"])) {
    $productos = $json_productos["productos"];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repaso Act 3</title>
    <style>
        .enlace {
            background: none;
            border: none;
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 80%;
            margin: 0 auto;
        }

        th {
            background-color: lightgray;
        }

        .text-c {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Repaso Actividad 3</h1>
    <form action="index.php" method="post">
        Bienvenido <strong><?= $usu_log["usuario"] ?></strong> -
        <button class="enlace" type="submit" name="btnSalir">Salir</button>
    </form>

    <h2 class="text-c">Listado de productos</h2>
    <?php
    if (!isset($productos)) {
        echo "<p class='text-c'>" . $json_productos["mensaje"] . "</p>";
    } else {
    ?>
        <table>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>PVP (€)</th>
            </tr>
            <?php
            foreach ($productos as $prod) {
                echo "<tr>";
                echo "<td>" . $prod["cod"] . "</td>";
                echo "<td>" . $prod["nombre_corto"] . "</td>";
                echo "<td>" . $prod["PVP"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    }
    ?>
</body>

</html>