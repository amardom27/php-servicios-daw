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

// $app->get('/logueado', function ($request) {

//     $id_usuario = $request->getParam("id_usuario");
//     echo json_encode(logueado($id_usuario));
// });


// $app->post('/login', function ($request) {

//     $datos_login[] = $request->getParam("usuario");
//     $datos_login[] = $request->getParam("clave");


//     echo json_encode(login($datos_login));
// });

$app->get('/horarioProfesor/{id_usuario}', function ($request) {

    $id_usuario = $request->getAttribute("id_usuario");
    echo json_encode(horario_profesor($id_usuario));
});

$app->get('/horarioGrupo/{id_grupo}', function ($request) {

    $id_grupo = $request->getAttribute("id_grupo");
    echo json_encode(horario_grupo($id_grupo));
});

$app->get('/grupos', function () {
    //echo json_encode(obtener_grupos());
    $test = validateToken();
    if (is_array($test)) {
        if (isset($test["usuario"])) {
            if ($test["usuario"]["tipo"] == "admin") {
                echo json_encode(obtener_grupos());
            } else {
                echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio."));
            }
        } else {
            echo json_encode($test);
        }
    } else {
        echo json_encode(array("no_auth" => "No tienes permiso para usar el servicio."));
    }
});

$app->get('/aulas', function () {
    echo json_encode(obtener_aulas());
});


$app->get('/profesores/{dia}/{hora}/{id_grupo}', function ($request) {

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    echo json_encode(profesores_grupo($dia, $hora, $id_grupo));
});

$app->get('/profesoresLibres/{dia}/{hora}/{id_grupo}', function ($request) {

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    echo json_encode(profesores_libres($dia, $hora, $id_grupo));
});


$app->delete('/borrarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}', function ($request) {

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    $id_usuario = $request->getAttribute("id_usuario");
    echo json_encode(borrar_profesor($dia, $hora, $id_grupo, $id_usuario));
});

$app->post('/insertarProfesor/{dia}/{hora}/{id_grupo}/{id_usuario}/{id_aula}', function ($request) {

    $dia = $request->getAttribute("dia");
    $hora = $request->getAttribute("hora");
    $id_grupo = $request->getAttribute("id_grupo");
    $id_usuario = $request->getAttribute("id_usuario");
    $id_aula = $request->getAttribute("id_aula");
    echo json_encode(insertar_profesor($dia, $hora, $id_grupo, $id_usuario, $id_aula));
});

$app->run();
