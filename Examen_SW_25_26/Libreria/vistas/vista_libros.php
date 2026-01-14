<?php
$url = DIR_SERV . "/obtenerLibros";

$respuesta = consumir_servicios_REST($url, "GET");
$json_libros = json_decode($respuesta, true);

if (!$json_libros) {
    session_destroy();
    die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
}
if (isset($json_libros["error"])) {
    session_destroy();
    die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_libros["error"] . "</p>"));
}

if (isset($json_libros["libros"])) {
    $libros = $json_libros["libros"];
}

if (isset($libros)) {
    echo "<div class='container'>";
    foreach ($libros as $libro) {
        $img_src = "./images/" . $libro["portada"];
?>
        <div class="libro-cont">
            <img src="<?= $img_src ?>" alt="Imagen portada">
            <p><?= $libro["titulo"] ?> - <?= $libro["precio"] ?> €</p>
        </div>
<?php
    }
    echo "</div>";
}
?>