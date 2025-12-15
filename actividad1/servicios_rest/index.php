<?php

require __DIR__ . '/Slim/autoload.php';

require "../../const_globales/env.php";
require "src/func_ctes.php";

$app = new \Slim\App;

$app->get("/productos", function () {
    echo json_encode(obtener_productos());
});

$app->get("/producto/{codigo}", function ($request) {
    $cod = $request->getAttribute("codigo");

    echo json_encode(obtener_producto_por_id($cod));
});

$app->post("/producto/insertar", function ($request) {
    $datos[] = $request->getParam("cod");
    $datos[] = $request->getParam("nombre");
    $datos[] = $request->getParam("nombre_corto");
    $datos[] = $request->getParam("descripcion");
    $datos[] = $request->getParam("PVP");
    $datos[] = $request->getParam("familia");

    echo json_encode(insertar_producto($datos));
});

$app->put("/producto/actualizar/{codigo}", function ($request) {
    $datos[] = $request->getParam("cod");
    $datos[] = $request->getParam("nombre");
    $datos[] = $request->getParam("nombre_corto");
    $datos[] = $request->getParam("descripcion");
    $datos[] = $request->getParam("PVP");
    $datos[] = $request->getParam("familia");

    $datos[] = $request->getAttribute("codigo");

    echo json_encode(actualizar_producto($datos));
});

$app->delete("/producto/borrar/{codigo}", function ($request) {
    $cod = $request->getAttribute("codigo");

    echo json_encode(borrar_producto_por_id($cod));
});

$app->get("/familias", function () {
    echo json_encode(obtener_familias());
});

$app->get("/repetido/{tabla}/{columna}/{valor}", function ($request) {
    $tabla = $request->getAttribute("tabla");
    $columna = $request->getAttribute("columna");
    $valor = $request->getAttribute("valor");

    echo json_encode(repetido_insertar($tabla, $columna, $valor));
});

$app->get("/repetido/{tabla}/{columna}/{valor}/{columna_id}/{valor_id}", function ($request) {
    $tabla = $request->getAttribute("tabla");
    $columna = $request->getAttribute("columna");
    $valor = $request->getAttribute("valor");
    $columna_id = $request->getAttribute("columna_id");
    $valor_id = $request->getAttribute("valor_id");

    echo json_encode(repetido_actualizar($tabla, $columna, $valor, $columna_id, $valor_id));
});

// Una vez creado servicios los pongo a disposición
$app->run();
