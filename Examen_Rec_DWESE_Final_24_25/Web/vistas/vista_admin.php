<?php

if (isset($_POST["aula"])) {

    $headers[] = "Authorization: Bearer " . $_SESSION["token"];
    $url = DIR_SERV . "/horarioGrupo/" . $_POST["aula"];
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

    if (isset($_POST["btnEditar"])) {

        $headers[] = "Authorization: Bearer " . $_SESSION["token"];
        $url = DIR_SERV . "/profesores/" . $_POST["dia"] . "/" . $_POST["hora"] . "/" . $_POST["aula"];
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

        $profesores = $json_profesores["profesores_aula"];
        var_dump($profesores);
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
    } ?>
</body>

</html>