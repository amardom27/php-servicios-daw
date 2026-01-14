<?php
if (isset($_POST["btnQuitar"])) {


    $url = DIR_API_HORARIO . "/borrarProfesor/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["btnQuitar"];
    $respuesta = consumir_servicios_REST($url, "DELETE");
    $json_borrar = json_decode($respuesta, true);
    if (!$json_borrar) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_borrar["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_borrar["error"] . "</strong></p>"));
    }

    $_SESSION["mensaje"] = "Profesor quitado con éxito";
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];
    header("Location:index.php");
    exit;
}

if (isset($_POST["btnAgregar"])) {


    $url = DIR_API_HORARIO . "/insertarProfesor/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["profesor"] . "/" . $_POST["aula"];
    $respuesta = consumir_servicios_REST($url, "POST");
    $json_insertar = json_decode($respuesta, true);
    if (!$json_insertar) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_insertar["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_insertar["error"] . "</strong></p>"));
    }

    $_SESSION["mensaje"] = "Profesor añadido con éxito";
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["grupo"] = $_POST["grupo"];
    header("Location:index.php");
    exit;
}


if (isset($_SESSION["dia"])) {
    $_POST["dia"] = $_SESSION["dia"];
    $_POST["hora"] = $_SESSION["hora"];
    $_POST["grupo"] = $_SESSION["grupo"];
    unset($_SESSION["dia"]);
    unset($_SESSION["grupo"]);
    unset($_SESSION["hora"]);
}


if (isset($_POST["dia"])) {


    $url = DIR_API_HORARIO . "/profesores/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"];
    $respuesta = consumir_servicios_REST($url, "GET");
    $json_profesores = json_decode($respuesta, true);
    if (!$json_profesores) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_profesores["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_profesores["error"] . "</strong></p>"));
    }

    $profesores_dia_hora_grupo = $json_profesores["profesores"];


    $url = DIR_API_HORARIO . "/profesoresLibres/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"];
    $respuesta = consumir_servicios_REST($url, "GET");
    $json_profesores_libres = json_decode($respuesta, true);
    if (!$json_profesores_libres) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_profesores_libres["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_profesores_libres["error"] . "</strong></p>"));
    }

    $no_profesores_dia_hora_grupo = $json_profesores_libres["profesores_libres"];



    $url = DIR_API_HORARIO . "/aulas";
    $respuesta = consumir_servicios_REST($url, "GET");
    $json_aulas = json_decode($respuesta, true);
    if (!$json_aulas) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_aulas["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_aulas["error"] . "</strong></p>"));
    }

    $aulas = $json_aulas["aulas"];
}


if (isset($_POST["grupo"])) {
    $url = DIR_API_HORARIO . "/horarioGrupo/" . $_POST["grupo"];
    $respuesta = consumir_servicios_REST($url, "GET");
    $json_horario_grupo = json_decode($respuesta, true);
    if (!$json_horario_grupo) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
    }

    if (isset($json_horario_grupo["error"])) {
        session_destroy();
        die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_horario_grupo["error"] . "</strong></p>"));
    }

    foreach ($json_horario_grupo["horario"] as $tupla) {
        if (isset($horario_grupo[$tupla["dia"]][$tupla["hora"]]))
            $horario_grupo[$tupla["dia"]][$tupla["hora"]] .= "<br>" . $tupla["usuario"] . "(" . $tupla["aula"] . ")";
        else
            $horario_grupo[$tupla["dia"]][$tupla["hora"]] = $tupla["usuario"] . "(" . $tupla["aula"] . ")";
    }
}

$headers[] = "Authorization: Bearer " . $_SESSION["token"];
$url = DIR_API_HORARIO . "/grupos";
$respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
$json_grupos = json_decode($respuesta, true);
if (!$json_grupos) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $url . "</strong></p>"));
}

if (isset($json_grupos["error"])) {
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>" . $json_grupos["error"] . "</strong></p>"));
}

if (isset($json_respuesta["no_auth"])) {
    session_unset();
    $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}

$grupos = $json_grupos["grupos"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Final PHP</title>
    <style>
        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer
        }

        .text_centrado {
            text-align: center
        }

        table,
        th,
        td {
            border: 1px solid black
        }

        table {
            border-collapse: collapse;
            margin: 0 auto;
            width: 90%;
            text-align: center
        }

        th {
            background-color: #CCC
        }

        .mensaje {
            color: blue;
            font-size: 1.25em
        }
    </style>
</head>

<body>
    <h1>Examen Final PHP</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <button type="submit" class="enlace" name="btnCerrarSesion">Salir</button></p>
    </form>
    <h2>Horario de los grupos</h2>
    <form action="index.php" method="post">
        <p>
            <label for="grupo">Elija un grupo: </label>
            <select name="grupo" id="grupo">
                <?php
                foreach ($grupos as $tupla) {
                    if (isset($_POST["grupo"]) && $_POST["grupo"] == $tupla["id_grupo"]) {
                        echo "<option selected value='" . $tupla["id_grupo"] . "'>" . $tupla["nombre"] . "</option>";
                        $nombre_grupo = $tupla["nombre"];
                    } else
                        echo "<option value='" . $tupla["id_grupo"] . "'>" . $tupla["nombre"] . "</option>";
                }
                ?>
            </select>
            <button name="btnVerHorario" type="submit">Ver Horario</button>
        </p>
    </form>

    <?php
    if (isset($_POST["grupo"])) {
        echo "<h2 class='text_centrado'>Horario del grupo: " . $nombre_grupo . "</h2>";
        echo "<table>";
        echo "<tr>";
        echo "<th></th>";

        for ($i = 1; $i <= count(DIAS); $i++)
            echo "<th>" . DIAS[$i] . "</th>";

        echo "</tr>";

        for ($hora = 1; $hora <= count(HORAS); $hora++) {
            echo "<tr>";
            echo "<td>" . HORAS[$hora] . "</td>";
            if ($hora == 4) {
                echo "<td colspan='5'>RECREO</td>";
            } else {
                for ($dia = 1; $dia <= count(DIAS); $dia++) {
                    if (isset($horario_grupo[$dia][$hora]))
                        echo "<td>" . $horario_grupo[$dia][$hora];
                    else
                        echo "<td>";

                    echo "<form action='index.php' method='post'>";
                    echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                    echo "<input type='hidden' name='dia' value='" . $dia . "'>";
                    echo "<input type='hidden' name='hora' value='" . $hora . "'>";
                    echo "<button class='enlace' type='submit' name='btnEditar'>Editar</button>";
                    echo "</form>";
                    echo "</td>";
                }
            }

            echo "</tr>";
        }

        echo "</table>";

        if (isset($_POST["dia"])) {
            if ($_POST["hora"] < 4)
                echo "<h2>Editando la " . $_POST["hora"] . "º Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h2>";
            else
                echo "<h2>Editando la " . ($_POST["hora"] - 1) . "º Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h2>";

            if (isset($_SESSION["mensaje"])) {
                echo "<p class='mensaje'>" . $_SESSION["mensaje"] . "</p>";
                unset($_SESSION["mensaje"]);
            }


            echo "<table>";
            echo "<tr><th>Profesor (Aula)</th><th>Acción</th></tr>";
            foreach ($profesores_dia_hora_grupo as $tupla) {
                echo "<tr>";
                echo "<td>" . $tupla["usuario"] . " (" . $tupla["aula"] . ")</td>";
                echo "<td>";
                echo "<form action='index.php' method='post'>";
                echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
                echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'>";
                echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'>";
                echo "<button class='enlace' type='submit' value ='" . $tupla["id_usuario"] . "' name='btnQuitar'>Quitar</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<br>";
            echo "<form class='text_centrado' action='index.php' method='post'>";
            echo "<p>";
            echo "<label for='profesor'>Elija profesor: </label>";
            echo "<select name='profesor' id='profesor'>";
            foreach ($no_profesores_dia_hora_grupo as $tupla)
                echo "<option value='" . $tupla["id_usuario"] . "'>" . $tupla["nombre"] . "</option>";
            echo "</select>";
            echo "<label for='aula'>Elija aula: </label>";
            echo "<select name='aula' id='aula'>";
            foreach ($aulas as $tupla)
                echo "<option value='" . $tupla["id_aula"] . "'>" . $tupla["nombre"] . "</option>";
            echo "</select>";
            echo "<button type='submit' name='btnAgregar'>Añadir</button>";
            echo "</p>";
            echo "<input type='hidden' name='grupo' value='" . $_POST["grupo"] . "'>";
            echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "'>";
            echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "'>";
            echo "</form>";
        }
    }
    ?>
</body>

</html>