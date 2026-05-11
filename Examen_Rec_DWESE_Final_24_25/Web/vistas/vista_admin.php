<?php

// Volver a mostrar las tablas
if (isset($_SESSION["dia"])) {

    $_POST["dia"] = $_SESSION["dia"];
    $_POST["hora"] = $_SESSION["hora"];
    $_POST["aula"] = $_SESSION["aula"];

    unset($_SESSION["dia"]);
    unset($_SESSION["hora"]);
    unset($_SESSION["aula"]);
}

if (isset($_POST["btnBorrar"])) {

    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $url = DIR_SERV . "/borrarProfesor" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["prof"] . "/" . $_POST["aula"];
    $respuesta = consumir_servicios_JWT_REST($url, "DELETE", $headers);
    $json_borrar = json_decode($respuesta, true);

    if (!$json_borrar) {
        session_destroy();
        die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_borrar["error"])) {
        session_destroy();
        die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_borrar["error"] . "</p>"));
    }
    if (isset($json_borrar["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    $_SESSION["mensaje_acc"] = $json_borrar["mensaje"];

    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["aula"] = $_POST["aula"];

    header("Location: index.php");
    exit;
}

if (isset($_POST["btnAgregar"])) {

    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $url = DIR_SERV . "/insertarProfesor" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["grupo"] . "/" . $_POST["prof"] . "/" . $_POST["aula"];
    $respuesta = consumir_servicios_JWT_REST($url, "POST", $headers);
    $json_insertar = json_decode($respuesta, true);

    if (!$json_insertar) {
        session_destroy();
        die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_insertar["error"])) {
        session_destroy();
        die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_insertar["error"] . "</p>"));
    }
    if (isset($json_insertar["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    $_SESSION["mensaje_acc"] = $json_insertar["mensaje"];

    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["hora"] = $_POST["hora"];
    $_SESSION["aula"] = $_POST["aula"];

    header("Location: index.php");
    exit;
}

if (isset($_POST["aula"])) {

    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $url = DIR_SERV . "/horarioGrupo" . "/" . $_POST["aula"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_horario = json_decode($respuesta, true);

    if (!$json_horario) {
        session_destroy();
        die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_horario["error"])) {
        session_destroy();
        die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_horario["error"] . "</p>"));
    }
    if (isset($json_horario["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    $horario = [];
    foreach ($json_horario["horario"] as $key => $value) {

        if (isset($horario[$value["dia"]][$value["hora"]])) {
            $horario[$value["dia"]][$value["hora"]] .= $value["usuario"] . "(" . $value["nombre"] . ")</br>";
            continue;
        }

        $horario[$value["dia"]][$value["hora"]] = $value["usuario"] . "(" . $value["nombre"] . ")</br>";
    }

    if (isset($_POST["dia"])) {

        $headers[] = "Authorization: Bearer " . $_SESSION["token"];
        $url = DIR_SERV . "/profesores" . "/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["aula"];
        $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
        $json_profesores_aula = json_decode($respuesta, true);

        if (!$json_profesores_aula) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_profesores_aula["error"])) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_profesores_aula["error"] . "</p>"));
        }
        if (isset($json_profesores_aula["no_auth"])) {
            session_unset();
            $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
            header("Location:index.php");
            exit;
        }

        $profesores_aula = $json_profesores_aula["profesores_aula"];

        $headers[] = "Authorization: Bearer " . $_SESSION["token"];
        $url = DIR_SERV . "/profesores";
        $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
        $json_profesores = json_decode($respuesta, true);

        if (!$json_profesores) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_profesores["error"])) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_profesores["error"] . "</p>"));
        }
        if (isset($json_profesores["no_auth"])) {
            session_unset();
            $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
            header("Location:index.php");
            exit;
        }

        $profesores = $json_profesores["profesores"];

        $headers[] = "Authorization: Bearer " . $_SESSION["token"];
        $url = DIR_SERV . "/grupos";
        $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
        $json_grupos = json_decode($respuesta, true);

        if (!$json_grupos) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_grupos["error"])) {
            session_destroy();
            die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_grupos["error"] . "</p>"));
        }
        if (isset($json_grupos["no_auth"])) {
            session_unset();
            $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
            header("Location:index.php");
            exit;
        }

        $grupos = $json_grupos["grupos"];
    }
}

$headers[] = "Authorization: Bearer " . $_SESSION["token"];
$url = DIR_SERV . "/aulas";
$respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
$json_aulas = json_decode($respuesta, true);

if (!$json_aulas) {
    session_destroy();
    die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_aulas["error"])) {
    session_destroy();
    die(error_page("Examen Rec Final PHP", "<h1>Examen Rec Final PHP</h1><p>" . $json_aulas["error"] . "</p>"));
}
if (isset($json_aulas["no_auth"])) {
    session_unset();
    $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}

$aulas = $json_aulas["aulas"];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Rec Final PHP</title>
    <style>
        body {
            padding-bottom: 3rem;
        }

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

        .mb-0 {
            margin-bottom: 0;
        }

        .final {
            text-align: center;
            margin-block: 2rem;
        }

        .azul {
            color: blue;
        }
    </style>
</head>

<body>
    <h1>Examen Rec Final PHP</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>

    <h2>Ocupación de las aulas</h2>
    <form action="" method="post">
        <label for="aula">Elija un aula: </label>
        <select name="aula" id="aula">
            <?php
            foreach ($aulas as $key => $value) {
                if (isset($_POST["aula"]) && $value["id_aula"] == $_POST["aula"]) {
                    $nom_aula = $value["nombre"];
                    echo "<option selected value='" . $value["id_aula"] . "'>" . $value["nombre"] . "</option>";
                    continue;
                }
                echo "<option value='" . $value["id_aula"] . "'>" . $value["nombre"] . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="btnVerAula">Ver Ocupación</button>
    </form>

    <?php if (isset($_POST["aula"])) { ?>
        <h3 class="text-center">Ocupación del Aula: <?= $nom_aula ?? "" ?></h3>
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

                    if (isset($horario[$j][$i])) {
                        echo $horario[$j][$i];
                    }
            ?>
                    <form action="" method="post">
                        <input type="hidden" name="aula" value="<?= $_POST["aula"] ?>">
                        <input type="hidden" name="dia" value="<?= $j ?>">
                        <input type="hidden" name="hora" value="<?= $i ?>">
                        <button class="enlace" type="submit" name="btnEditar">Editar</button>
                    </form>
            <?php

                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>

        <?php
        if (isset($_SESSION["mensaje_acc"])) {
            echo "<p class='azul text-center'>¡¡ " . $_SESSION["mensaje_acc"] . " !!</p>";
            unset($_SESSION["mensaje_acc"]);
        }
        ?>


        <?php
        if (isset($_POST["dia"])) {

            if ($_POST["hora"] > 3) {
                echo "<h2>Editando la " . $_POST["hora"] - 1 . "º Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h2>";
            } else {
                echo "<h2>Editando la " . $_POST["hora"] . "º Hora (" . HORAS[$_POST["hora"]] . ") del " . DIAS[$_POST["dia"]] . "</h2>";
            }
        ?>

            <table>
                <tr>
                    <th>Profesor (Grupo)</th>
                    <th>Acción</th>
                </tr>

                <?php
                foreach ($profesores_aula as $key => $prof) {
                    echo "<tr>";
                    echo "<td>" . $prof["usuario"] . " (" . $prof["grupo"] . ")</td>";
                ?>
                    <td>
                        <form action="" method="post" class="mb-0">

                            <input type="hidden" name="aula" value="<?= $_POST["aula"] ?>">
                            <input type="hidden" name="dia" value="<?= $_POST["dia"] ?>">
                            <input type="hidden" name="hora" value="<?= $_POST["hora"] ?>">
                            <input type="hidden" name="grupo" value="<?= $prof["id_grupo"] ?>">
                            <input type="hidden" name="prof" value="<?= $prof["id_usuario"] ?>">

                            <button type="submit" class="enlace" name="btnBorrar">Quitar</button>

                        </form>
                    </td>
                <?php
                    echo "</tr>";
                }
                ?>
            </table>

            <div class="final">
                <form action="" method="post">
                    <label for="prof">Elija un profesor: </label>
                    <select name="prof" id="prof">
                        <?php
                        foreach ($profesores as $key => $value) {

                            if (isset($_POST["prof"]) && $value["id_usuario"] == $_POST["prof"]) {
                                echo "<option selected value='" . $value["id_usuario"] . "'>" . $value["usuario"] . "</option>";
                                continue;
                            }

                            echo "<option value='" . $value["id_usuario"] . "'>" . $value["usuario"] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="grupo"> y un grupo: </label>
                    <select name="grupo" id="grupo">
                        <?php
                        foreach ($grupos as $key => $value) {

                            if (isset($_POST["grupo"]) && $value["id_grupo"] == $_POST["grupo"]) {
                                $nom_aula = $value["nombre"];
                                echo "<option selected value='" . $value["id_grupo"] . "'>" . $value["nombre"] . "</option>";
                                continue;
                            }

                            echo "<option value='" . $value["id_grupo"] . "'>" . $value["nombre"] . "</option>";
                        }
                        ?>
                    </select>

                    <input type="hidden" name="aula" value="<?= $_POST["aula"] ?>">
                    <input type="hidden" name="dia" value="<?= $_POST["dia"] ?>">
                    <input type="hidden" name="hora" value="<?= $_POST["hora"] ?>">

                    <button type="submit" name="btnAgregar">Añadir</button>
                </form>
            </div>

    <?php
        }
    } ?>
</body>

</html>