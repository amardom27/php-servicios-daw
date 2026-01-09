<?php
$url = API_HORARIOS . "/grupos";

$respuesta = consumir_servicios_REST($url, "GET");
$json_grupos = json_decode($respuesta, true);

if (!$json_grupos) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
}

if (isset($json_grupos["error"])) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
}

$grupos = $json_grupos["grupos"];

if (isset($_POST["grupo"])) {
    $url = API_HORARIOS . "/horarioGrupo" . "/" . $_POST["grupo"];

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_hor_grupos = json_decode($respuesta, true);

    if (!$json_hor_grupos) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
    }

    if (isset($json_hor_gruposs["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
    }

    foreach ($json_hor_grupos["horario"] as $hor) {
        if (isset($datos_horario[$hor["dia"]][$hor["hora"]])) {
            $datos_horario[$hor["dia"]][$hor["hora"]]["usuario"] .= ("<br>" . $hor["usuario"]);
        } else {
            $datos_horario[$hor["dia"]][$hor["hora"]]["usuario"] = $hor["usuario"];
        }
    }
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

        .center {
            text-align: center;
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
        <button type="submit" name="btnSalir">Salir</button>
    </form>

    <h2>Horario de los grupos</h2>
    <form action="index.php" method="post">
        <label for="grupo">Elija el grupo: </label>
        <select name="grupo" id="grupo">
            <?php
            foreach ($grupos as $grupo) {
                if (isset($_POST["grupo"]) && $_POST["grupo"] == $grupo["id_grupo"]) {
                    $nombre_grupo = $grupo["nombre"];
                    echo "<option selected value='" . $grupo["id_grupo"] . "'>" . $grupo["nombre"] . "</option>";
                } else {
                    echo "<option value='" . $grupo["id_grupo"] . "'>" . $grupo["nombre"] . "</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="btnVerHorario">Ver horario</button>
    </form>

    <?php
    if (isset($_POST["grupo"])) {
    ?>
        <h3 class='center'>Horario del grupo: <?= $nombre_grupo ?></h3>
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
                echo "<td>" . HORAS[$i] . "</td>";

                if ($i == 4) {
                    echo "<td colspan='5' class='text-c'>RECREO</td>";
                    continue;
                }

                for ($j = 1; $j <= count(DIAS); $j++) {
                    if (isset($datos_horario[$j][$i])) {
                        echo "<td>";
                        echo $datos_horario[$j][$i]["usuario"];

                        echo '<form action="index.php" method="post">';
                        echo '<button type="submit" class="enlace">Editar</button>';
                        echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                        echo '</form>';

                        echo "</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    }
    ?>
</body>

</html>