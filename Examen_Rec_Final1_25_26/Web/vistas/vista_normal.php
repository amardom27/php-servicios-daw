<?php

if (isset($_POST["dia"])) {

    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $url = DIR_SERV . "/usuariosGuardia" . "/" . $_POST["dia"] . "/" . $_POST["hora"];
    $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
    $json_usuarios = json_decode($respuesta, true);

    if (!$json_usuarios) {
        session_destroy();
        die(error_page("Gestión de Guardias", "<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_usuarios["error"])) {
        session_destroy();
        die(error_page("Gestión de Guardias", "<h1>Gestión de Guardias</h1><p>" . $json_usuarios["error"] . "</p>"));
    }

    if (isset($json_usuarios["no_auth"])) {
        session_unset();
        $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
        header("Location:index.php");
        exit;
    }

    $prof_guardia = $json_usuarios["usuarios"];

    if (isset($_POST["btnDetalles"])) {

        $headers[] = "Authorization: Bearer " . $_SESSION["token"];
        $url = DIR_SERV . "/usuario" . "/" . $_POST["btnDetalles"];
        $respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
        $json_detalles = json_decode($respuesta, true);

        if (!$json_detalles) {
            session_destroy();
            die(error_page("Gestión de Guardias", "<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_detalles["error"])) {
            session_destroy();
            die(error_page("Gestión de Guardias", "<h1>Gestión de Guardias</h1><p>" . $json_detalles["error"] . "</p>"));
        }

        if (isset($json_detalles["no_auth"])) {
            session_unset();
            $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
            header("Location:index.php");
            exit;
        }

        $detalles_usu = $json_detalles["usuario"];
    }
}

$headers[] = "Authorization: Bearer " . $_SESSION["token"];
$url = DIR_SERV . "/deGuardia" . "/" . $datos_usu_log["id_usuario"];
$respuesta = consumir_servicios_JWT_REST($url, "GET", $headers);
$json_horario = json_decode($respuesta, true);

if (!$json_horario) {
    session_destroy();
    die(error_page("Gestión de Guardias", "<h1>Gestión de Guardias</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_horario["error"])) {
    session_destroy();
    die(error_page("Gestión de Guardias", "<h1>Gestión de Guardias</h1><p>" . $json_horario["error"] . "</p>"));
}

if (isset($json_horario["no_auth"])) {
    session_unset();
    $_SESSION["mensaje_seguridad"] = "El tiempo de sesión de la API ha expirado";
    header("Location:index.php");
    exit;
}

$horario_guardia = [];
foreach ($json_horario["de_guardia"] as $tupla) {
    $horario_guardia[$tupla["dia"]][$tupla["hora"]] = true;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Guardias</title>
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
        td,
        th {
            border: 1px solid black;
        }

        th {
            background-color: lightgray;
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 80%;
            margin: 0 auto;
        }

        form {
            margin: 0;
        }

        .left {
            text-align: left;
            padding-left: 2rem;
        }
    </style>
</head>

<body>
    <h1>Gestión de Guardias</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["usuario"]; ?></strong> -
        <form class="enlinea" action="index.php" method="post">
            <button class="enlace" type="submit" name="btnSalir">Salir</button>
        </form>
    </div>

    <h2>Equipos de Guardias del IES Mar de Alborán</h2>
    <table>
        <tr>
            <th>A</th>
            <?php
            for ($i = 1; $i <= count(DIAS); $i++) {
                echo "<th>" . DIAS[$i] . "</th>";
            }
            ?>
        </tr>
        <?php
        for ($i = 1; $i <= 6; $i++) {

            if ($i == 4) {
                echo "<tr>";
                echo "<td colspan='6'>RECREO</td>";
                echo "</tr>";
            }

            echo "<tr>";
            echo "<td>" . $i . " º Hora</td>";

            for ($j = 1; $j <= count(DIAS); $j++) {
                if (isset($horario_guardia[$j][$i])) {

                    $equipo = (5 * ($i - 1)) + $j;

                    echo "<td>";
                    echo "<form method='post'>";

                    echo "<button name='btnHorario' class='enlace' type='submit'>Equipo " . $equipo . "</button>";

                    echo "<input type='hidden' name='dia' value='" . $j . "' />";
                    echo "<input type='hidden' name='hora' value='" . $i . "' />";
                    echo "<input type='hidden' name='equipo' value='" . $equipo . "' />";

                    echo "</form>";
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
    if (isset($_POST["dia"])) {
    ?>
        <h2>Equipo de guardia <?= $_POST["equipo"] ?></h2>
        <h3><?= DIAS[$_POST["dia"]] ?> a <?= $_POST["hora"] ?>º hora</h3>

        <table>
            <th>Profesores de Guardia</th>
            <th>Información del profesor con id: <?php if (isset($_POST["btnDetalles"])) echo $_POST["btnDetalles"] ?></th>

            <?php
            $flag = true;
            foreach ($prof_guardia as $key => $value) {
                echo "<tr>";
                echo "<td>";
                echo "<form method='post'>";

                echo "<button name='btnDetalles' value='" . $value["id_usuario"] . "' class='enlace' type='submit'>" . $value["nombre"] . "</button>";

                echo "<input type='hidden' name='dia' value='" . $_POST["dia"] . "' />";
                echo "<input type='hidden' name='hora' value='" . $_POST["hora"] . "' />";
                echo "<input type='hidden' name='equipo' value='" . $_POST["equipo"] . "' />";

                echo "</form>";
                echo "</td>";

                if ($flag) {
                    echo "<td class='left' rowspan='" . count($prof_guardia) . "'>";

                    if (isset($_POST["btnDetalles"])) {
                        echo "<p><strong>Nombre:</strong> " . $detalles_usu["nombre"] . "</p>";
                        echo "<p><strong>Usuario:</strong> " . $detalles_usu["usuario"] . "</p>";
                        echo "<p><strong>Contraseña:</strong></p>";

                        if (isset($detalles_usu["email"])) {
                            echo "<p><strong>Email:</strong> " . $detalles_usu["email"] . "</p>";
                        } else {
                            echo "<p><strong>Email:</strong> Email no disponible</p>";
                        }
                    }

                    echo "</td>";
                    $flag = false;
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