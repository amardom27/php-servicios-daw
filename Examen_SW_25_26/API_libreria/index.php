<?php

require __DIR__ . '/Slim/autoload.php';

require "src/funciones_CTES.php";

$app = new \Slim\App;

$app->get('/logueado/{id_usuario}', function ($request) {

    echo json_encode(logueado($request->getAttribute("id_usuario")));
});


$app->post('/login', function ($request) {

    $datos_login[] = $request->getParam("lector");
    $datos_login[] = $request->getParam("clave");

    echo json_encode(login($datos_login));
});

$app->get("/obtenerLibros", function ($request) {
    echo json_encode(obtener_libros());
});

$app->get("/obtenerLibro/{referencia}", function ($request) {
    $ref = $request->getAttribute("referencia");

    echo json_encode(obtener_libro($ref));
});

$app->post("/crearLibro", function ($request) {
    $datos_libro[] = $request->getParam("referencia");
    $datos_libro[] = $request->getParam("titulo");
    $datos_libro[] = $request->getParam("autor");
    $datos_libro[] = $request->getParam("descripcion");
    $datos_libro[] = $request->getParam("precio");

    echo json_encode(crear_libro($datos_libro));
});

$app->put("/actualizarLibro/{referencia}", function ($request) {
    $datos_libro[] = $request->getParam("titulo");
    $datos_libro[] = $request->getParam("autor");
    $datos_libro[] = $request->getParam("descripcion");
    $datos_libro[] = $request->getParam("precio");

    $datos_libro[] = $request->getAttribute("referencia");


    echo json_encode(actualizar_libro($datos_libro));
});

$app->delete("/borrarLibro/{referencia}", function ($request) {
    $ref = $request->getAttribute("referencia");

    echo json_encode(borrar_libro($ref));
});

$app->put("/actualizarPortada/{referencia}", function ($request) {
    $portada = $request->getParam("portada");
    $ref = $request->getAttribute("referencia");

    echo json_encode(actualizar_portada($portada, $ref));
});

$app->get("/repetido/{tabla}/{columna}/{valor}", function ($request) {
    $tabla = $request->getAttribute("tabla");
    $columna = $request->getAttribute("columna");
    $valor = $request->getAttribute("valor");

    echo json_encode(repetido_insertar($tabla, $columna, $valor));
});

$app->get("/repetido/{tabla}/{columna}/{valor}/{columna_key}/{valor_key}", function ($request) {
    $tabla = $request->getAttribute("tabla");
    $columna = $request->getAttribute("columna");
    $valor = $request->getAttribute("valor");
    $columna_id = $request->getAttribute("columna_key");
    $valor_id = $request->getAttribute("valor_key");

    echo json_encode(repetido_editar($tabla, $columna, $valor, $columna_id, $valor_id));
});



$app->run();
