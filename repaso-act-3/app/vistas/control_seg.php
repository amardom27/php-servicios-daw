<?php
$url = API_TIENDA . "/logueado";
$datos_login["id_usuario"] = $_SESSION["logueado"];

$respuesta = consumir_servicios_REST($url, "GET", $datos_login);
$json_logueado = json_decode($respuesta, true);

if (!$json_logueado) {
    session_destroy();
    die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($json_logueado["error"])) {
    session_destroy();
    die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_login["error"] . "</p>"));
}

if (isset($json_logueado["mensaje"])) {
    session_unset();
    $_SESSION["seguridad"] = "Usted ya no se encuentra en la BD.";

    header("Location: index.php");
    exit;
}

$usu_log = $json_logueado["usuario"];

if (time() - $_SESSION["ult_acc"] > TIEMPO_INAC * 60) {
    session_unset();
    $_SESSION["seguridad"] = "Tiempo de sesion expirado.";

    header("Location: index.php");
    exit;
}

$_SESSION["ult_acc"] = time();
