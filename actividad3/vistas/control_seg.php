<?php
$url = API_TIENDA . "/logueado";
$datos_logueado["id"] = $_SESSION["logueado"];

$respuesta = consumir_servicios_REST($url, "POST", $datos_logueado);
$json_logueado = json_decode($respuesta, true); // true para que sea un array asociativo

// No hay respuesta
if (!$json_logueado) {
    session_destroy();
    die(error_page("Actividad 3", "<h1>Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}
// La respuesta es un error
if (isset($json_logueado["error_bd"])) {
    session_destroy();
    die(error_page("Actividad 3", "<h1>Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($json_logueado["mensaje"])) {
    // Me han baneado, ya no estas en la base de datos
    // No destroy para mandar un nueva variable de informacion
    session_unset();
    $_SESSION["seguridad"] = "Usted ya no se encuentra en la BD.";

    header("Location: index.php");
    exit;
}

$usuario = $json_logueado["usuario"];

if (time() - $_SESSION["ult_acc"] > TIEMPO_INAC * 60) {
    session_unset();
    $_SESSION["seguridad"] = "Su tiempo de sessión ha expirado.";

    header("Location: index.php");
    exit;
}

$_SESSION["ult_acc"] = time();
