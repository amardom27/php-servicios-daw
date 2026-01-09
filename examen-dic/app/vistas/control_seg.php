<?php
$url = API_HORARIOS . "/logueado";
$datos_login["id"] = $_SESSION["logueado"];

$respuesta = consumir_servicios_REST($url, "GET", $datos_login);
$json_logueado = json_decode($respuesta, true);

// Error en la respuesta
if (!$json_logueado) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}
// Error en la consulta
if (isset($json_logueado["error"])) {
    session_destroy();
    die(error_page("Examen Dic Servicios", "<h1>Examen Dic Servicios</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}
// No se encuentra el usuario (se ha borrado de la BD)
if (isset($json_logueado["mensaje"])) {
    session_unset();
    $_SESSION["seguridad"] = "Usted ya no se encuentra en la BD.";

    header("Location: index.php");
    exit;
}
// Guardamos los datos del usuario logueado
$usu_log = $json_logueado["usuario"];

// Comprobamos el tiempo de inactividad
if (time() - $_SESSION["ult_acc"] > TIEMPO_INAC * 60) {
    session_unset();
    $_SESSION["seguridad"] = "Tiempo de sesion expirado.";

    header("Location: index.php");
    exit;
}
// Actualizamos el tiempo
$_SESSION["ult_acc"] = time();
