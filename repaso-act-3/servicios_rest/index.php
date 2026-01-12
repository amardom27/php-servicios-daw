<?php

require __DIR__ . '/Slim/autoload.php';

const NOMBRE_BD = "bd_tienda";
require "../../const_globales/env.php";
require "src/func_ctes.php";

$app = new \Slim\App;

$app->post("/login", function ($request) {
    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");

    echo json_encode(login($usuario, $clave));
});

$app->get("/logueado", function ($request) {
    $id_usuario = $request->getParam("id_usuario");

    echo json_encode(logueado($id_usuario));
});

$app->get("/productos", function () {
    echo json_encode(productos());
});

$app->get("/producto/{codigo}", function ($request) {
    $codigo = $request->getAttribute("codigo");

    echo json_encode(detalle_producto($codigo));
});

$app->get("/familias", function () {
    echo json_encode(familias());
});

$app->post("/producto/insertar", function ($request) {
    $datos[] = $request->getParam("cod");
    $datos[] = $request->getParam("nombre");
    $datos[] = $request->getParam("nombre_corto");
    $datos[] = $request->getParam("descripcion");
    $datos[] = $request->getParam("PVP");
    $datos[] = $request->getParam("familia");

    echo json_encode(nuevo_producto($datos));
});

$app->delete("/producto/borrar/{codigo}", function ($request) {
    $codigo = $request->getAttribute("codigo");

    echo json_encode(borrar_producto($codigo));
});

$app->put("/producto/actualizar", function ($request) {
    $datos[] = $request->getParam("cod");
    $datos[] = $request->getParam("nombre");
    $datos[] = $request->getParam("nombre_corto");
    $datos[] = $request->getParam("descripcion");
    $datos[] = $request->getParam("PVP");
    $datos[] = $request->getParam("familia");
    $datos[] = $request->getParam("cod_original");

    echo json_encode(editar_producto($datos));
});

$app->get("/repetido/{tabla}/{columna}/{valor}", function ($request) {
    $tabla = $request->getAttribute("tabla");
    $columna = $request->getAttribute("columna");
    $valor = $request->getAttribute("valor");

    echo json_encode(repetido_ins($tabla, $columna, $valor));
});

$app->get("/repetido/{tabla}/{columna}/{valor}/{columna_id}/{valor_id}", function ($request) {
    $tabla = $request->getAttribute("tabla");
    $columna = $request->getAttribute("columna");
    $valor = $request->getAttribute("valor");
    $columna_id = $request->getAttribute("columna_id");
    $valor_id = $request->getAttribute("valor_id");

    echo json_encode(repetido_editar($tabla, $columna, $valor, $columna_id, $valor_id));
});

$app->get('/saludo/{codigo}', function ($request) {

    //$datos["cod"]=$request->getParam('cod');
    echo json_encode(array("mensaje" => "Hola " . $request->getAttribute('codigo')), JSON_FORCE_OBJECT);
});

// Una vez creado servicios los pongo a disposición
$app->run();
