<?php

require __DIR__ . '/Slim/autoload.php';

require "src/funciones_CTES.php";

$app = new \Slim\App;

$app->get('/logueado', function () {

    $test = validateToken();
    if (is_array($test)) {
        echo json_encode($test);
    } else
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
});


$app->post('/login', function ($request) {

    $datos_login[] = $request->getParam("usuario");
    $datos_login[] = $request->getParam("clave");


    echo json_encode(login($datos_login));
});

$app->get('/aulasLibres/{dia}/{hora}', function ($request) {

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");

    $test = validateToken();
    if (is_array($test)) {
        echo json_encode(get_aulas_libres($dia, $hora));
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }
});

$app->run();
