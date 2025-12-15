<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web - Teoría Servicios Web</title>
</head>

<body>
    <h1>Web - Teoría Servicios Web</h1>
    <?php
    const DIR_API = "http://localhost/proyectos/servicios_web/api_saludo";

    function consumir_servicios_REST($url, $metodo, $datos = null) {
        $llamada = curl_init();
        curl_setopt($llamada, CURLOPT_URL, $url);
        curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);

        if (isset($datos)) {
            curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));
        }
        $respuesta = curl_exec($llamada);
        curl_close($llamada);
        return $respuesta;
    }

    $url = DIR_API . '/saludo';
    $respuesta = consumir_servicios_REST($url, 'GET');
    $json = json_decode($respuesta, true);

    if (!$json) {
        die("<p>Error consumiendo el servicio rest: " . $url . "</p></body></html>");
    }
    echo "<p>Mensaje recibido: " . $json["mensaje"] . "</p>";
    ?>
</body>

</html>