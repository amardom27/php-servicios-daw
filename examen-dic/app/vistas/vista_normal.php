<?php
$url = API_HORARIOS . "/horarioProfesor" . "/" . $_SESSION["logueado"];

$respuesta = consumir_servicios_REST($url, "GET");
$json_hor_prof = json_decode($respuesta, true);

if (!$json_hor_prof) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($json_hor_prof["error"])) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_hor_prof["error"] . "</p>"));
}

foreach ($json_hor_prof["horario"] as $hor) {
    if (isset($datos_horario[$hor["dia"]][$hor["hora"]])) {
        $datos_horario[$hor["dia"]][$hor["hora"]]["grupo"] .= ("/" . $hor["grupo"]);
    } else {
        $datos_horario[$hor["dia"]][$hor["hora"]]["grupo"] = $hor["grupo"];
    }
    $datos_horario[$hor["dia"]][$hor["hora"]]["aula"] = $hor["aula"];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Dic Servicios</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 0 auto;
            text-align: center;
        }

        th {
            background-color: lightgray;
        }

        .enlace {
            background: none;
            border: none;
            text-decoration: underline;
            cursor: pointer;
            color: blue;
        }
    </style>
</head>

<body>
    <h1>Examen Dic Servicios</h1>
    <form action="index.php" method="post">
        Bienvenido <strong><?= $usu_log["nombre"] ?></strong> -
        <button class="enlace" type="submit" name="btnSalir">Salir</button>
    </form>

    <h2>Su horario</h2>
    <h3>Horario del profesor: <?= $usu_log["nombre"] ?></h3>
    <table>
        <tr>
            <th></th>
            <?php
            for ($i = 1; $i <= count(DIAS); $i++) {
                echo "<th>" . DIAS[$i] . "</th>";
            }
            ?>
        </tr>
        <?php
        for ($i = 1; $i <= count(HORAS); $i++) {
            echo "<tr>";
            echo "<th>" . HORAS[$i] . "</th>";

            if ($i == 4) {
                echo "<td colspan='5' class='text-c'>RECREO</td>";
                continue;
            }

            for ($j = 1; $j <= count(DIAS); $j++) {
                if (isset($datos_horario[$j][$i])) {
                    echo "<td>";
                    echo $datos_horario[$j][$i]["grupo"] . "<br>" . $datos_horario[$j][$i]["aula"];
                    echo "</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>