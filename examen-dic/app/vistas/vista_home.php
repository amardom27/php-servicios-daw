<?php
if (isset($_POST["btnLogin"])) {
    $error_usuario = $_POST["usuario"] == "";
    $error_clave = $_POST["clave"] == "";

    $error_form = $error_usuario || $error_clave;

    if (!$error_form) {
        $url = API_HORARIOS . "/login";

        $datos_login["usuario"] = $_POST["usuario"];
        $datos_login["clave"] = md5($_POST["clave"]);

        $respuesta = consumir_servicios_REST($url, "POST", $datos_login);
        $json_login = json_decode($respuesta, true);

        // Error en la respuesta
        if (!$json_login) {
            session_destroy();
            die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }
        // Error en la consulta
        if (isset($json_login["error"])) {
            session_destroy();
            die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }
        // No se encuentra el usuario (no esta en la BD)
        if (isset($json_login["mensaje"])) {
            $error_usuario = true;
        } else {
            $_SESSION["logueado"] = $json_login["usuario"]["id_usuario"];
            $_SESSION["ult_acc"] = time();

            header("Location: index.php");
            exit;
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
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Examen Dic Servicios</h1>
    <form action="index.php" method="post">
        <p>
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["btnLogin"])) echo $_POST["usuario"] ?>">
            <?php
            if (isset($_POST["btnLogin"]) && $error_usuario) {
                if ($_POST["usuario"] == "") {
                    echo "<span class='error'>* Campo vacio.</span>";
                } else {
                    echo "<span class='error'>* Credeciales invalidas.</span>";
                }
            }
            ?>
        </p>
        <p>
            <label for="clave">Clave: </label>
            <input type="password" name="clave" id="clave">
            <?php
            if (isset($_POST["btnLogin"]) && $error_clave) {
                echo "<span class='error'>* Campo vacio.</span>";
            }
            ?>
        </p>
        <button type="submit" name="btnLogin">Login</button>
    </form>
    <?php
    if (isset($_SESSION["seguridad"])) {
        echo "<p class='info'>" . $_SESSION["seguridad"] . "</p>";
    }
    ?>
</body>

</html>