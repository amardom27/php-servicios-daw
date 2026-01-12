<?php
$url = API_TIENDA . "/productos";

// Productos para la tabla
// No hace falta ponerlo al final porque saltamos al insertar, editar y borrar
$respuesta = consumir_servicios_REST($url, "GET");
$json_productos = json_decode($respuesta, true);

if (!$json_productos) {
    session_destroy();
    die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
}

if (isset($json_productos["error"])) {
    session_destroy();
    die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_productos["error"] . "</p>"));
}

if (isset($json_productos["productos"])) {
    $productos = $json_productos["productos"];
}

if (isset($_POST["btnDetalle"])) {
    $url = API_TIENDA . "/producto" . "/" . $_POST["btnDetalle"];

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_detalle = json_decode($respuesta, true);

    if (!$json_detalle) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($json_detalle["error"])) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_detalle["error"] . "</p>"));
    }

    if (isset($json_detalle["producto"])) {
        $detalles = $json_detalle["producto"];
    }
}

if (isset($_POST["btnContAgregar"])) {
    $error_codigo = $_POST["cod"] == "";
    // Comprobar que no esta repetido el codigo
    if (!$error_codigo) {
        // urlencode para los datos que vienen del usuario
        $url = API_TIENDA . "/repetido/producto/cod/" . urlencode($_POST["cod"]);

        $respuesta = consumir_servicios_REST($url, "GET");
        $json_repetido = json_decode($respuesta, true);

        if (!$json_repetido) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_repetido["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_repetido["error"] . "</p>"));
        }

        $error_codigo = $json_repetido["repetido"];
    }

    $error_nombre = $_POST["nombre_corto"] == "";
    // TODO Comprobar nombre_corto repetido
    if (!$error_nombre) {
        // urlencode para los datos que vienen del usuario
        $url = API_TIENDA . "/repetido/producto/nombre_corto/" . urlencode($_POST["nombre_corto"]);

        $respuesta = consumir_servicios_REST($url, "GET");
        $json_repetido = json_decode($respuesta, true);

        if (!$json_repetido) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_repetido["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_repetido["error"] . "</p>"));
        }

        $error_nombre = $json_repetido["repetido"];
    }

    $error_desc = $_POST["desc"] == "";
    $error_pvp = $_POST["pvp"] == "" || !is_numeric($_POST["pvp"]) || $_POST["pvp"] < 0;

    $error_form = $error_codigo || $error_nombre || $error_desc || $error_pvp;

    if (!$error_form) {
        $url = API_TIENDA . "/producto/insertar";

        // Pasamos los datos a la funcion (los recoge el endpoint)
        $datos_insertar["cod"] = $_POST["cod"];
        $datos_insertar["nombre"] = $_POST["nombre"];
        $datos_insertar["nombre_corto"] = $_POST["nombre_corto"];
        $datos_insertar["descripcion"] = $_POST["desc"];
        $datos_insertar["PVP"] = $_POST["pvp"];
        $datos_insertar["familia"] = $_POST["familia"];

        $respuesta = consumir_servicios_REST($url, "POST", $datos_insertar);
        $json_insertar = json_decode($respuesta, true);

        if (!$json_insertar) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_insertar["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_insertar["error"] . "</p>"));
        }

        // Redundante porque solo se devuelve mensaje de la api (no hay otra cosa como usuario)
        if (isset($json_insertar["mensaje"])) {
            $_SESSION["mensaje"] = $json_insertar["mensaje"];
            header("Location: index.php");
            exit;
        }
    }
}

if (isset($_POST["btnContEditar"])) {
    $error_codigo = $_POST["cod"] == "";
    // Comprobar que no esta repetido el codigo
    if (!$error_codigo) {
        // urlencode para los datos que vienen del usuario
        $url = API_TIENDA . "/repetido/producto/cod/" . urlencode($_POST["cod"]) . "/cod" . "/" . urlencode($_POST["btnContEditar"]);

        $respuesta = consumir_servicios_REST($url, "GET");
        $json_repetido = json_decode($respuesta, true);

        if (!$json_repetido) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_repetido["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_repetido["error"] . "</p>"));
        }

        $error_codigo = $json_repetido["repetido"];
    }

    $error_nombre = $_POST["nombre_corto"] == "";
    if (!$error_nombre) {
        // urlencode para los datos que vienen del usuario
        $url = API_TIENDA . "/repetido/producto/nombre_corto/" . urlencode($_POST["nombre_corto"]) . "/cod" . "/" . urlencode($_POST["btnContEditar"]);

        $respuesta = consumir_servicios_REST($url, "GET");
        $json_repetido = json_decode($respuesta, true);

        if (!$json_repetido) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_repetido["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_repetido["error"] . "</p>"));
        }

        $error_nombre = $json_repetido["repetido"];
    }

    $error_desc = $_POST["desc"] == "";
    $error_pvp = $_POST["pvp"] == "" || !is_numeric($_POST["pvp"]) || $_POST["pvp"] < 0;

    $error_form = $error_codigo || $error_nombre || $error_desc || $error_pvp;

    if (!$error_form) {
        $url = API_TIENDA . "/producto/actualizar";

        // Pasamos los datos a la funcion (los recoge el endpoint)
        $datos_insertar["cod"] = $_POST["cod"];
        $datos_insertar["nombre"] = $_POST["nombre"];
        $datos_insertar["nombre_corto"] = $_POST["nombre_corto"];
        $datos_insertar["descripcion"] = $_POST["desc"];
        $datos_insertar["PVP"] = $_POST["pvp"];
        $datos_insertar["familia"] = $_POST["familia"];
        $datos_insertar["cod_original"] = $_POST["btnContEditar"];

        $respuesta = consumir_servicios_REST($url, "PUT", $datos_insertar);
        $json_insertar = json_decode($respuesta, true);

        if (!$json_insertar) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
        }

        if (isset($json_insertar["error"])) {
            session_destroy();
            die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_insertar["error"] . "</p>"));
        }

        // Redundante porque solo se devuelve mensaje de la api (no hay otra cosa como usuario)
        if (isset($json_insertar["mensaje"])) {
            $_SESSION["mensaje"] = $json_insertar["mensaje"];
            header("Location: index.php");
            exit;
        }
    }
}

if (isset($_POST["btnAgregar"]) || (isset($_POST["btnContAgregar"]) && $error_form) || isset($_POST["btnEditar"]) || (isset($_POST["btnContEditar"]) && $error_form)) {
    $url = API_TIENDA . "/familias";

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_familias = json_decode($respuesta, true);

    if (!$json_familias) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($json_familias["error"])) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_familias["error"] . "</p>"));
    }

    if (isset($json_familias["familias"])) {
        $familias = $json_familias["familias"];
    }
}

if (isset($_POST["btnContBorrar"])) {
    $url = API_TIENDA . "/producto/borrar/" . $_POST["btnContBorrar"];

    $respuesta = consumir_servicios_REST($url, "DELETE", $datos_insertar);
    $json_borrar = json_decode($respuesta, true);

    if (!$json_borrar) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($json_borrar["error"])) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_borrar["error"] . "</p>"));
    }

    $_SESSION["mensaje"] = $json_borrar["mensaje"];
    header("Location: index.php");
    exit;
}

if (isset($_POST["btnEditar"]) || (isset($_POST["btnContEditar"]) && $error_form)) {
    $cod = $_POST["btnEditar"] ?? $_POST["btnContEditar"];

    $url = API_TIENDA . "/producto" . "/" . $cod;

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_editar = json_decode($respuesta, true);

    if (!$json_editar) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p>"));
    }

    if (isset($json_editar["error"])) {
        session_destroy();
        die(error_page("Repaso Act 3", "<h1>Repaso Actividad 3</h1><p>Error consumiendo el servicio: " . $url . "</p><p>Error: " . $json_editar["error"] . "</p>"));
    }

    if (!isset($json_borrar["mensaje"])) {
        $info_producto = $json_editar["producto"];
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repaso Act 3</title>
    <style>
        .enlace {
            background: none;
            border: none;
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            text-align: center;
            width: 80%;
            margin: 0 auto;
        }

        th {
            background-color: lightgray;
        }

        .text-c {
            text-align: center;
        }

        .info {
            color: blue;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Repaso Actividad 3</h1>
    <form action="index.php" method="post">
        Bienvenido <strong><?= $usu_log["usuario"] ?></strong> -
        <button class="enlace" type="submit" name="btnSalir">Salir</button>
    </form>

    <?php
    if (isset($_POST["btnDetalle"])) {
    ?>
        <div>
            <h3>Información del producto: <?= $detalles["cod"] ?></h3>

            <p><strong>Nombre: </strong><?= $detalles["nombre"] ?></p>
            <p><strong>Nombre Corto: </strong><?= $detalles["nombre_corto"] ?></p>
            <p><strong>Descripción: </strong><?= $detalles["descripcion"] ?></p>
            <p><strong>PVP: </strong><?= $detalles["PVP"] ?> €</p>
            <p><strong>Familia: </strong><?= $detalles["nom_familia"] ?></p>

            <form action="index.php" method="post">
                <p><button type="submit">Volver</button></p>
            </form>
        </div>
    <?php
    }

    if (isset($_POST["btnAgregar"]) || (isset($_POST["btnContAgregar"]) && $error_form) || isset($_POST["btnEditar"]) || isset($_POST["btnContEditar"]) && $error_form) {
        // Proteger el null si no hay info_producto
        $info_producto = $info_producto ?? [];

        $cod = $_POST["cod"] ?? $info_producto["cod"] ?? "";
        $nombre = $_POST["nombre"] ?? $info_producto["nombre"] ?? "";
        $nombre_corto = $_POST["nombre_corto"] ?? $info_producto["nombre_corto"] ?? "";
        $desc = $_POST["desc"] ?? $info_producto["descripcion"] ?? "";
        $pvp = $_POST["pvp"] ?? $info_producto["PVP"] ?? "";
    ?>
        <form action="index.php" method="post">
            <?php
            if (isset($_POST["btnAgregar"]) || (isset($_POST["btnContAgregar"]) && $error_form)) {
                echo "<h3>Insertar un nuevo producto</h3>";
            } else {
                echo "<h3>Editar producto</h3>";
            }
            ?>
            <p>
                <label for="cod">Código: </label>
                <input type="text" name="cod" id="cod" value="<?= $cod ?>">
                <?php
                if ((isset($_POST["btnContAgregar"]) || isset($_POST["btnContEditar"])) && $error_codigo) {
                    if ($_POST["cod"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else {
                        echo "<span class='error'>* Codigo repetido.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" id="nombre" value="<?= $nombre ?>">
            </p>
            <p>
                <label for="nombre_corto">Nombre corto: </label>
                <input type="text" name="nombre_corto" id="nombre_corto" value="<?= $nombre_corto ?>">
                <?php
                if ((isset($_POST["btnContAgregar"]) || isset($_POST["btnContEditar"])) && $error_nombre) {
                    if ($_POST["nombre_corto"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else {
                        echo "<span class='error'>* Nombre repetido.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="desc">Descripción: </label>
                <textarea name="desc" id="desc"><?= $desc ?></textarea>
                <?php
                if ((isset($_POST["btnContAgregar"]) || isset($_POST["btnContEditar"])) && $error_desc) {
                    if ($_POST["desc"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="pvp">PVP: </label>
                <input type="number" name="pvp" id="pvp" value="<?= $pvp ?>">
                <?php
                if ((isset($_POST["btnContAgregar"]) || isset($_POST["btnContEditar"])) && $error_pvp) {
                    if ($_POST["pvp"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else if (!is_numeric($_POST["pvp"])) {
                        echo "<span class='error'>* No es un numero.</span>";
                    } else {
                        echo "<span class='error'>* Valor incorrecto (menor que 0).</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="familia">Familia: </label>
                <select name="familia" id="familia">
                    <?php
                    foreach ($familias as $familia) {
                        if (isset($info_producto["familia"]) && $info_producto["familia"] == $familia["cod"]) {
                            echo "<option selected value='" . $familia["cod"] . "'>" . $familia["nombre"] . "</option>";
                        }
                        echo "<option value='" . $familia["cod"] . "'>" . $familia["nombre"] . "</option>";
                    }
                    ?>
                </select>
            </p>
            <button type="submit">Atrás</button>
            <?php
            if (isset($_POST["btnAgregar"]) || (isset($_POST["btnContAgregar"]) && $error_form)) {
            ?>
                <button type="submit" name="btnContAgregar">Agregar</button>
            <?php
            } else {
            ?>
                <button type="submit" name="btnContEditar" value="<?= $info_producto["cod"] ?>">Editar</button>
                <input type="hidden" name="h_nombre_corto" value="<?= $info_producto["nombre_corto"] ?>">
            <?php
            }
            ?>
        </form>
    <?php
    }
    ?>

    <h2 class="text-c">Listado de productos</h2>
    <?php
    if (isset($_SESSION["mensaje"])) {
        echo "<p class='info text-c'>" . $_SESSION["mensaje"] . "</p>";
        unset($_SESSION["mensaje"]);
    }

    if (isset($_POST["btnBorrar"])) {
    ?>
        <p>Va a borrar el producto <strong><?= $_POST["btnBorrar"] ?></strong>, ¿ está seguro ? </p>
        <form action="index.php" method="post">
            <button type="submit">Volver</button>
            <button type="submit" name="btnContBorrar" value="<?= $_POST["btnBorrar"] ?>">Continuar</button>
        </form>
    <?php
    }
    ?>
    <?php
    if (!isset($productos)) {
        echo "<p class='text-c'>" . $json_productos["mensaje"] . "</p>";
    } else {
    ?>
        <table>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>PVP (€)</th>
                <th>
                    <form action="index.php" method="post">
                        <button class="enlace" type="submit" name="btnAgregar">Producto+</button>
                    </form>
                </th>
            </tr>
            <?php
            foreach ($productos as $prod) {
            ?>
                <tr>
                    <form action="index.php" method="post">
                        <?php
                        echo "<td>";
                        echo "<button class='enlace' type='submit' name='btnDetalle' value='" . $prod["cod"] . "'>" . $prod["cod"] . "</button>";
                        echo "</td>";
                        echo "<td>" . $prod["nombre_corto"] . "</td>";
                        echo "<td>" . $prod["PVP"] . " €</td>";
                        echo "<td>";
                        echo "<button class='enlace' type='submit' name='btnBorrar' value='" . $prod["cod"] . "'>Borrar</button>";
                        echo " - ";
                        echo "<button class='enlace' type='submit' name='btnEditar' value='" . $prod["cod"] . "'>Editar</button>";
                        echo "</td>";
                        ?>
                    </form>
                </tr>
            <?php
            }
            ?>

        </table>
    <?php
    }
    ?>
</body>

</html>