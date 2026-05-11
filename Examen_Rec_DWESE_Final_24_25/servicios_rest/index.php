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

    $test = validateToken();
    if (is_array($test)) {

        $dia = $request->getAttribute("dia");
        $hora = $request->getAttribute("hora");

        echo json_encode(get_aulas_libres($dia, $hora));
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }

    // Version limpia
    // $test = validateToken();

    // if (!is_array($test) || $test["usuario"]["tipo"] !== "normal") {
    //     echo json_encode([
    //         "no_auth" => "No tienes permiso para usar el servicio"
    //     ]);
    //     return;
    // }

    // $dia = $request->getAttribute("dia");
    // $hora = $request->getAttribute("hora");

    // echo json_encode(get_aulas_libres($dia, $hora));

});

$app->get('/aulas', function ($request) {
    $test = validateToken();
    if (is_array($test)) {
        echo json_encode(get_aulas());
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }
});

$app->get('/horarioGrupo/{id_aula}', function ($request) {

    $id = $request->getAttribute("id_aula");

    $test = validateToken();
    if (is_array($test)) {
        echo json_encode(get_horario_grupo($id));
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio"));
    }
});

$app->get('/profesores/{dia}/{hora}/{id_aula}', function ($request) {

    $test = validateToken();

    if (!is_array($test) || $test["usuario"]["tipo"] !== "admin") {
        echo json_encode(["no_auth" => "No tienes permiso para usar el servicio"]);
        return;
    }

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_aula = $request->getAttribute("id_aula");

    echo json_encode(get_profesores_aula($dia, $hora, $id_aula));
});

$app->get('/profesores', function ($request) {

    $test = validateToken();

    if (!is_array($test) || $test["usuario"]["tipo"] !== "admin") {
        echo json_encode(["no_auth" => "No tienes permiso para usar el servicio"]);
        return;
    }

    echo json_encode(get_profesores());
});

$app->get('/grupos', function ($request) {

    $test = validateToken();

    if (!is_array($test) || $test["usuario"]["tipo"] !== "admin") {
        echo json_encode(["no_auth" => "No tienes permiso para usar el servicio"]);
        return;
    }

    echo json_encode(get_grupos());
});

$app->post('/insertarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}/{id_aula}', function ($request) {

    $test = validateToken();

    if (!is_array($test) || $test["usuario"]["tipo"] !== "admin") {
        echo json_encode(["no_auth" => "No tienes permiso para usar el servicio"]);
        return;
    }

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    $id_usuario = $request->getAttribute("id_usuario");
    $id_aula = $request->getAttribute("id_aula");

    echo json_encode(insertar_profesor($dia, $hora, $id_grupo, $id_usuario, $id_aula));
});

$app->delete('/borrarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}/{id_aula}', function ($request) {

    $test = validateToken();

    if (!is_array($test) || $test["usuario"]["tipo"] !== "admin") {
        echo json_encode(["no_auth" => "No tienes permiso para usar el servicio"]);
        return;
    }

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    $id_usuario = $request->getAttribute("id_usuario");
    $id_aula = $request->getAttribute("id_aula");

    echo json_encode(borrar_profesor($dia, $hora, $id_grupo, $id_usuario, $id_aula));
});

$app->run();
