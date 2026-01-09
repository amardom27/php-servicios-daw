<?php
const API_HORARIOS = "http://localhost/proyectos/servicios_web/examen-dic/servicios_rest";
const TIEMPO_INAC = 10; // minutos
const HORAS = [
    1 => "8:15 - 9:15",
    "9:15 - 10:15",
    "10:15 - 11:15",
    "11:15 - 11:45",
    "11:45 - 12:45",
    "12:45 - 13:45",
    "13:45 - 14:45"
];
const DIAS = [
    1 => "Lunes",
    "Martes",
    "Miércoles",
    "Jueves",
    "Viernes"
];

function consumir_servicios_REST($url, $metodo, $datos = null) {
    $llamada = curl_init();

    curl_setopt($llamada, CURLOPT_URL, $url);
    curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);

    if (isset($datos)) {
        curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));
    }

    $respuesta = curl_exec($llamada);
    $llamada = null; // curl_close esta deprecated

    return $respuesta;
}

function error_page($title, $body) {
    return '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body>' . $body . '</body>
</html>';
}
