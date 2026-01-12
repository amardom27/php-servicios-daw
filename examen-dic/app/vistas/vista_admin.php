<?php
$url = API_HORARIOS . "/grupos";

$respuesta = consumir_servicios_REST($url, "GET");
$json_grupos = json_decode($respuesta, true);

if (!$json_grupos) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($json_grupos["error"])) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
}

$grupos = $json_grupos["grupos"];

if (isset($_POST["btnBorrar"])) {
    $url = API_HORARIOS . "/borrarProfesor" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["btnBorrar"];

    $respuesta = consumir_servicios_REST($url, "DELETE");
    $json_borrar = json_decode($respuesta, true);

    if (!$json_borrar) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($json_borrar["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_borrar["error"] . "</p>"));
    }

    $_SESSION["mensaje"] = $json_borrar["mensaje"];

    // Para prevenir volver a hacer la operacion en la recarga de la pagina
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];

    header("Location: index.php");
    exit;
}

if (isset($_POST["btnAgregar"])) {
    $url = API_HORARIOS . "/agregarProfesor" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["prof"] . "/" . $_POST["aula"];

    $respuesta = consumir_servicios_REST($url, "POST");
    $json_agregar = json_decode($respuesta, true);

    if (!$json_agregar) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($json_agregar["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_agregar["error"] . "</p>"));
    }

    $_SESSION["mensaje"] = $json_agregar["mensaje"];

    // Para prevenir volver a hacer la operacion en la recarga de la pagina
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];

    header("Location: index.php");
    exit;
}

if (isset($_SESSION["dia"])) {
    $_POST["dia"] = $_SESSION["dia"];
    $_POST["hora"] = $_SESSION["hora"];
    $_POST["grupo"] = $_SESSION["grupo"];
    // Para que se vuelva a mostrar la segunda tabla
    $_POST["btnEditar"] = true;

    unset($_SESSION["dia"]);
    unset($_SESSION["hora"]);
    unset($_SESSION["grupo"]);
}

if (isset($_POST["grupo"])) {
    $url = API_HORARIOS . "/horarioGrupo" . "/" . $_POST["grupo"];

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_hor_grupos = json_decode($respuesta, true);

    if (!$json_hor_grupos) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
    }

    if (isset($json_hor_grupos["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_grupos["error"] . "</p>"));
    }

    foreach ($json_hor_grupos["horario"] as $hor) {
        if (isset($datos_horario[$hor["dia"]][$hor["hora"]])) {
            $datos_horario[$hor["dia"]][$hor["hora"]]["usuario"] .= ("<br>" . $hor["usuario"] . " (" . $hor["aula"] . ")");
        } else {
            $datos_horario[$hor["dia"]][$hor["hora"]]["usuario"] = $hor["usuario"] . " (" . $hor["aula"] . ")";
        }
    }
}

if (isset($_POST["btnEditar"]) || isset($_POST["btnBorrar"]) || isset($_POST["btnAgregar"])) {
    $url = API_HORARIOS . "/profesores" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"];

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_profesores = json_decode($respuesta, true);

    if (!$json_profesores) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_profesores["error"] . "</p>"));
    }

    if (isset($json_profesores["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_profesores["error"] . "</p>"));
    }

    $profesores = $json_profesores["profesores"];

    // Prof libres
    $url = API_HORARIOS . "/profesoresLibres" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"];

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_profesores_libres = json_decode($respuesta, true);

    if (!$json_profesores_libres) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_profesores_libres["error"] . "</p>"));
    }

    if (isset($json_profesores_libres["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_profesores_libres["error"] . "</p>"));
    }

    $profesores_libres = $json_profesores_libres["profesores_libres"];

    // Aulas
    $url = API_HORARIOS . "/aulas";

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_aulas = json_decode($respuesta, true);

    if (!$json_aulas) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_aulas["error"] . "</p>"));
    }

    if (isset($json_aulas["error"])) {
        session_destroy();
        die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p><p>" . $json_aulas["error"] . "</p>"));
    }

    $aulas = $json_aulas["aulas"];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Dic Servicios</title>
    <style>
        body {
            padding-bottom: 3rem;
        }

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

        .cont {
            margin-top: 1.2rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Examen Dic Servicios</h1>
    <form action="index.php" method="post">
        Bienvenido <strong><?= $usu_log["nombre"] ?></strong> -
        <button class="enlace" type="submit" name="btnSalir">Salir</button>
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
                        echo '<button type="submit" class="enlace" name="btnEditar">Editar</button>';
                        echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                        echo "<input type='hidden' name='hora' value='" . $i . "'>";
                        echo "<input type='hidden' name='dia' value='" . $j . "'>";
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
    if (isset($_POST["btnEditar"]) || isset($_POST["btnBorrar"]) || isset($_POST["btnAgregar"])) {
        echo "<h3>Editando la " . $_POST["hora"] . " ª Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h3>";

        if (isset($_SESSION["mensaje"])) {
            echo "<p>" . $_SESSION["mensaje"] . "</p>";
        }
    ?>
        <table>
            <tr>
                <th>Profesor (aula)</th>
                <th>Acción</th>
            </tr>
            <?php
            foreach ($profesores as $prof) {
                echo "<tr>";
                echo "<td>" . $prof["usuario"] . " (" . $prof["aula"] . ")</td>";
            ?>
                <td>
                    <form action="index.php" method="post">
                        <button type="submit" class="enlace" value="<?= $prof["id_usuario"] ?>" name="btnBorrar">Borrar</button>

                        <input type="hidden" name="grupo" value="<?= $_POST["grupo"] ?>">
                        <input type="hidden" name="hora" value="<?= $_POST["hora"] ?>">
                        <input type="hidden" name="dia" value="<?= $_POST["dia"] ?>">
                    </form>
                </td>
            <?php
                echo "</tr>";
            }
            ?>
        </table>

    <?php
    }

    if (isset($_POST["btnEditar"]) || isset($_POST["btnBorrar"]) || isset($_POST["btnAgregar"])) {
    ?>
        <div class="cont">
            <form action="index.php" method="post">
                <label for="prof">Eliga profesor: </label>
                <select name="prof" id="prof">
                    <?php
                    foreach ($profesores_libres as $prof_libre) {
                        echo "<option value='" . $prof_libre["id_usuario"] . "'>" . $prof_libre["usuario"] . "</option>";
                    }
                    ?>
                </select>
                <label for="aula">Elija aula: </label>
                <select name="aula" id="aula">
                    <?php
                    foreach ($aulas as $aula) {
                        echo "<option value='" . $aula["id_aula"] . "'>" . $aula["nombre"] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="btnAgregar">Añadir</button>

                <input type="hidden" name="grupo" value="<?= $_POST["grupo"] ?>">
                <input type="hidden" name="hora" value="<?= $_POST["hora"] ?>">
                <input type="hidden" name="dia" value="<?= $_POST["dia"] ?>">
            </form>
        </div>
    <?php
    }
    ?>
</body>

</html>