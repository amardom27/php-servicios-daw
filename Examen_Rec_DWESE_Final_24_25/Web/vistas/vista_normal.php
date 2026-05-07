<?php

$aulas_libres = [];

for ($i = 1; $i <= 7; $i++) {

    if ($i == 4) {
        continue;
    }

    for ($j = 1; $j <= 5; $j++) {

        $headers[] = "Authorization: Bearer " . $_SESSION["token"];
        $url = DIR_SERV . "/aulasLibres/" . $j . "/" . $i;
        $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
        $json_respuesta = json_decode($respuesta, true);

        if (!$json_respuesta) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_respuesta["error"])) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_respuesta["error"] . "</p>"));
        }
        if (isset($json_respuesta["no_auth"])) {
            session_unset();
            $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
            header("Location:index.php");
            exit;
        }

        $aulas_libres[$j][$i] = $json_respuesta["aulas_libres"];
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Rec Final PHP</title>
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

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: lightgray;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Examen Rec Final PHP</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>

    <h2 class='text-center'>Aulas libres</h2>
    <table>
        <th></th>
        <?php
        for ($i = 1; $i <= count(DIAS); $i++) {
            echo "<th>" . DIAS[$i] . "</th>";
        }
        ?>

        <?php
        foreach (HORAS as $i => $hora) {

            echo "<tr>";
            echo "<th>" . $hora . "</th>";

            if ($i == 4) {
                echo "<td colspan='5'>Recreo</td>";
                echo "</tr>";
                continue;
            }

            foreach (DIAS as $j => $dia) {
                echo "<td>";

                if (isset($aulas_libres[$j][$i])) {

                    foreach ($aulas_libres[$j][$i] as $key => $value) {
                        echo $value["nombre"] . " <br>";
                    }
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>