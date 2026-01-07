<?php

require __DIR__ . '/Slim/autoload.php';

const NOMBRE_BD = "bd_horarios_exam";
require "../../const_globales/env.php";
require "src/func_ctes.php";

$app = new \Slim\App;

$app->post("/login", function ($request) {
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave"); // Encriptada con md5

    echo json_encode(login($usuario, $clave));
});

$app->post("/logueado", function ($request) {
    $id = $request->getParam("id");

    echo json_encode(logueado($id));
});

$app->get('/saludo/{codigo}', function ($request) {
    //$datos["cod"]=$request->getParam('cod');
    echo json_encode(array("mensaje" => "Hola " . $request->getAttribute('codigo')), JSON_FORCE_OBJECT);
});

// Una vez creado servicios los pongo a disposición
$app->run();
