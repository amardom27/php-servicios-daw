<?php

require __DIR__ . '/Slim/autoload.php';

$app = new \Slim\App;

$app->get('/saludo', function () {

    echo json_encode(array("mensaje" => "Hola mundo"));
});

$app->get('/saludo/{codigo}', function ($request) {

    //$datos["cod"]=$request->getParam('cod');
    echo json_encode(array("mensaje" => "Hola " . $request->getAttribute('codigo')), JSON_FORCE_OBJECT);
});

$app->post('/crearSaludo', function ($request) {
    // En el para decidimos con que nombre nos tienen que mandar el mensaje
    $array["mensaje"] = $request->getParam('msj');
    echo json_encode($array);
});

$app->delete('/borrarSaludo/{id}', function ($request) {
    $array["mensaje"] = "Borrando el saludo con id " . $request->getAttribute('id');
    echo json_encode($array, JSON_FORCE_OBJECT);
});

$app->put('/actualizarSaludo/{id}', function ($request) {
    $array["mensaje"] = "Actulizando el saludo con id " . $request->getAttribute('id') . " al nuevo valor " . $request->getParam('valor');
    echo json_encode($array);
});

// Una vez creado servicios los pongo a disposición
$app->run();
