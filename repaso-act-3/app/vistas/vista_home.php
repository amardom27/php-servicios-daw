<?php
if (isset($_POST["btnLogin"])) {
    $error_usuario = $_POST["usuario"] == "";
    $error_clave = $_POST["clave"] == "";

    $error_form = $error_usuario || $error_clave;

    if (!$error_form) {
        $url = API_TIENDA . "/login";

        $datos_login["usuario"] = $_POST["usuario"];
        $datos_login["clave"] = md5($_POST["clave"]);

        $respuesta = consumir_servicios_REST($url, "POST", $datos_login);
        $json_login = json_decode($respuesta, true);

        if (!$json_login) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_login["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_login["error"] . "</p>"));
        }

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
    <title>Repaso Act 3</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Repaso Actividad 3</h1>
    <form action="index.php" method="post">
        <p>
            <label for="usuario">Usuario: </label>
            <input type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["usuario"])) echo $_POST["usuario"] ?>">
            <?php
            if (isset($_POST["btnLogin"]) && $error_usuario) {
                if ($_POST["usuario"] == "") {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                } else {
                    echo "<span class='error'>* Credenciales inválidas.</span>";
                }
            }
            ?>
        </p>
        <p>
            <label for="clave">Clave: </label>
            <input type="password" name="clave" id="clave">
            <?php
            if (isset($_POST["btnLogin"]) && $error_clave) {
                echo "<span class='error'>* Campo obligatorio.</span>";
            }
            ?>
        </p>
        <button type="submit" name="btnLogin">Login</button>
    </form>
</body>

</html>