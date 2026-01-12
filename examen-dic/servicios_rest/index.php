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

$app->get("/logueado", function ($request) {
    $id = $request->getParam("id");

    echo json_encode(logueado($id));
});

$app->get("/horarioProfesor/{id_usuario}", function ($request) {
    $id_usuario = $request->getAttribute("id_usuario");

    echo json_encode(horario_profesor_id($id_usuario));
});

$app->get("/horarioGrupo/{id_grupo}", function ($request) {
    $id_grupo = $request->getAttribute("id_grupo");

    echo json_encode(horario_profesor_grupo($id_grupo));
});

$app->get("/grupos", function () {
    echo json_encode(grupos());
});

$app->get("/aulas", function () {
    echo json_encode(aulas());
});

$app->get("/profesores/{dia}/{hora}/{id_grupo}", function ($request) {
    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");

    echo json_encode(horarios_profesores_grupo($dia, $hora, $id_grupo));
});

$app->get("/profesoresLibres/{dia}/{hora}/{id_grupo}", function ($request) {
    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");

    echo json_encode(horarios_profesores_libres($dia, $hora, $id_grupo));
});

$app->delete("/borrarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}", function ($request) {
    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    $id_usuario = $request->getAttribute("id_usuario");

    echo json_encode(borrar_profesor($dia, $hora, $id_grupo, $id_usuario));
});

$app->post("/agregarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}/{id_aula}", function ($request) {
    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    $id_usuario = $request->getAttribute("id_usuario");
    $id_aula = $request->getAttribute("id_aula");

    echo json_encode(agregar_profesor($dia, $hora, $id_grupo, $id_usuario, $id_aula));
});

$app->get('/saludo/{codigo}', function ($request) {
    //$datos["cod"]=$request->getParam('cod');
    echo json_encode(array("mensaje" => "Hola " . $request->getAttribute('codigo')), JSON_FORCE_OBJECT);
});

// Una vez creado servicios los pongo a disposición
$app->run();
