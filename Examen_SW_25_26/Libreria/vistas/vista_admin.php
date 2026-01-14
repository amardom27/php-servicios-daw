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

if (isset($_POST["btnAgregar"])) {
    $error_ref = $_POST["referencia"] == "" || !is_numeric($_POST["referencia"]) || $_POST["referencia"] < 0;
    if (!$error_ref) {
        $url = DIR_SERV . "/repetido/libros/referencia/" . urldecode($_POST["referencia"]);

        $respuesta = consumir_servicios_REST($url, "GET");
        $json_repetido = json_decode($respuesta, true);

        if (!$json_repetido) {
            session_destroy();
            die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_repetido["error"])) {
            session_destroy();
            die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_repetido["error"] . "</p>"));
        }

        $error_ref = $json_repetido["repetido"];
    }

    $error_titulo = $_POST["titulo"] == "";
    $error_autor = $_POST["autor"] == "";
    $error_desc = $_POST["descripcion"] == "";
    $error_precio = $_POST["precio"] == "" || !is_numeric($_POST["precio"]) || $_POST["precio"] < 0;

    $error_portada = $_FILES["portada"] != "" && (
        $_FILES["portada"]["error"]
        || !tiene_ext($_FILES["portada"]["tmp_name"])
        || $_FILES["portada"]["name"] == ""
        || $_FILES["portada"]["size"] > 500 * 1024
    );

    $error_form = $error_ref || $error_titulo || $error_autor || $error_desc || $error_precio || $error_portada;

    if (!$error_form) {
        $url = DIR_SERV . "/crearLibro";

        $datos_insertar["referencia"] = $_POST["referencia"];
        $datos_insertar["titulo"] = $_POST["titulo"];
        $datos_insertar["autor"] = $_POST["autor"];
        $datos_insertar["descripcion"] = $_POST["descripcion"];
        $datos_insertar["precio"] = $_POST["precio"];

        $respuesta = consumir_servicios_REST($url, "POST", $datos_insertar);
        $json_insertar = json_decode($respuesta, true);

        if (!$json_insertar) {
            session_destroy();
            die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_insertar["error"])) {
            session_destroy();
            die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_insertar["error"] . "</p>"));
        }

        if (isset($json_insertar["mensaje"])) {
            $_SESSION["mensaje"] = $json_insertar["mensaje"];

            $move = move_uploaded_file($_FILES["portada"]["tmp_name"], "images/" . $_FILES["portada"]["name"]);

            if (!$move) {
            } else {
                $url = DIR_SERV . "/actualizarPortada" . "/" . urlencode($_POST["referencia"]);

                $datos_insertar["portada"] = $_FILES["portada"]["name"];

                $respuesta = consumir_servicios_REST($url, "PUT", $datos_insertar);
                $json_portada = json_decode($respuesta, true);

                if (!$json_portada) {
                    session_destroy();
                    die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
                }
                if (isset($json_portada["error"])) {
                    session_destroy();
                    die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_portada["error"] . "</p>"));
                }

                if (isset($json_portada["mensaje"])) {
                    $_SESSION["mensaje"] .= $json_portada["mensaje"];
                }
            }

            //header("Location: index.php");
            //exit;
        }
    }
}

if (isset($_POST["btnContEditar"])) {
    $error_titulo = $_POST["titulo"] == "";
    $error_autor = $_POST["autor"] == "";
    $error_desc = $_POST["descripcion"] == "";
    $error_precio = $_POST["precio"] == "" || !is_numeric($_POST["precio"]) || $_POST["precio"] < 0;

    $error_portada = $_FILES["portada"] != "" && (
        $_FILES["portada"]["error"]
        || !tiene_ext($_FILES["portada"]["tmp_name"])
        || $_FILES["portada"]["name"] == ""
        || $_FILES["portada"]["size"] > 500 * 1024
    );

    $error_form = $error_titulo || $error_autor || $error_desc || $error_precio || $error_portada;

    if (!$error_form) {
        $url = DIR_SERV . "/actualizarLibro" . "/" . urldecode($_POST["btnContEditar"]);

        $datos_insertar["titulo"] = $_POST["titulo"];
        $datos_insertar["autor"] = $_POST["autor"];
        $datos_insertar["descripcion"] = $_POST["descripcion"];
        $datos_insertar["precio"] = $_POST["precio"];

        $respuesta = consumir_servicios_REST($url, "PUT", $datos_insertar);
        $json_editar = json_decode($respuesta, true);

        if (!$json_editar) {
            session_destroy();
            die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
        }
        if (isset($json_editar["error"])) {
            session_destroy();
            die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_editar["error"] . "</p>"));
        }

        if (isset($json_editar["mensaje"])) {
            $_SESSION["mensaje"] = $json_editar["mensaje"];

            @$move = move_uploaded_file($_FILES["portada"]["tmp_name"], "images/nueva.jpg");

            if (!$move) {
                $_SESSION["mensaje"] .= "Con la imagen por defecto.";
            } else {
                $url = DIR_SERV . "/actualizarPortada" . "/" . urlencode($_POST["btnContEditar"]);

                $datos_insertar["portada"] = $_FILES["portada"]["name"];

                $respuesta = consumir_servicios_REST($url, "PUT", $datos_insertar);
                $json_portada = json_decode($respuesta, true);

                if (!$json_portada) {
                    session_destroy();
                    die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
                }
                if (isset($json_portada["error"])) {
                    session_destroy();
                    die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_portada["error"] . "</p>"));
                }

                if (isset($json_portada["mensaje"])) {
                    $_SESSION["mensaje"] .= $json_portada["mensaje"];

                    @$move = move_uploaded_file($_FILES["portada"]["tmp_name"], "images/nueva.jpg");

                    if (!$move) {
                    } else {
                        $url = DIR_SERV . "/actualizarPortada" . "/" . urlencode($_POST["referencia"]);

                        $datos_insertar["portada"] = $_FILES["portada"]["name"];

                        $respuesta = consumir_servicios_REST($url, "PUT", $datos_insertar);
                        $json_portada = json_decode($respuesta, true);

                        if (!$json_portada) {
                            session_destroy();
                            die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
                        }
                        if (isset($json_portada["error"])) {
                            session_destroy();
                            die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_portada["error"] . "</p>"));
                        }

                        if (isset($json_portada["mensaje"])) {
                            $_SESSION["mensaje"] .= $json_portada["mensaje"];
                        }
                    }
                }
            }
            header("Location: index.php");
            exit;
        }
    }
}

if (isset($_POST["btnContBorrar"])) {
    $url = DIR_SERV . "/obtenerLibro" . "/" . urlencode($_POST["btnContBorrar"]);

    $respuesta = consumir_servicios_REST($url, "GET");
    $json_borrar = json_decode($respuesta, true);

    if (!$json_borrar) {
        session_destroy();
        die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_borrar["error"])) {
        session_destroy();
        die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_borrar["error"] . "</p>"));
    }

    if (isset($json_borrar["libro"])) {
        if ($json_borrar["libro"]["portada"] != "no_imagen.jpg") {
            unlink("images" . $json_borrar);
        }
    }

    $url = DIR_SERV . "/borrarLibro" . "/" . urlencode($_POST["btnContBorrar"]);

    $respuesta = consumir_servicios_REST($url, "DELETE");
    $json_borrar = json_decode($respuesta, true);

    if (!$json_borrar) {
        session_destroy();
        die(error_page("Gestión Libros", "<h1>Librería</h1><p>Error consumiendo el servicio Rest: <strong>" . $url . "</strong></p>"));
    }
    if (isset($json_borrar["error"])) {
        session_destroy();
        die(error_page("Gestión Libros", "<h1>Librería</h1><p>" . $json_borrar["error"] . "</p>"));
    }

    if (isset($json_borrar["mensaje"])) {
        $_SESSION["mensaje"] = $json_borrar["mensaje"];
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Libros</title>
    <style>
        .enlinea {
            display: inline
        }

        .enlace {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        .error {
            color: red;
        }

        .info {
            color: blue;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            text-align: center;
            margin: 0 auto;
        }

        th {
            background-color: lightgray;
        }

        label {
            display: inline-block;
            width: 100px;
        }
    </style>
</head>

<body>
    <h1>Librería</h1>
    <div>
        Bienvenido <strong><?php echo $datos_usu_log["lector"]; ?></strong> - <form class="enlinea" action="index.php" method="post"><button class="enlace" type="submit" name="btnSalir">Salir</button></form>
    </div>
    <h2>Listado de los libros</h2>
    <table>
        <tr>
            <th>Ref</th>
            <th>Título</th>
            <th>Acción</th>
        </tr>
        <?php
        if (isset($libros)) {
            foreach ($libros as $libro) {
                echo "<tr>";
                echo "<form action='index.php' method='post'>";

                echo "<td>" . $libro["referencia"] . "</td>";

                echo "<td>";
                echo "<button type='submit' class='enlace'>" . $libro["titulo"] . "</button>";
                echo "</td>";

                echo "<td>";
                echo "<button type='submit' class='enlace' name='btnBorrar' value='" . $libro["referencia"] . "'>Borrar</button>";
                echo " - ";
                echo "<button type='submit' class='enlace' name='btnEditar' value='" . $libro["referencia"] . "'>Editar</button>";
                echo "</td>";

                echo "</form>";
                echo "</tr>";
            }
        }
        ?>
    </table>

    <?php
    if (isset($_SESSION["mensaje"])) {
        echo "<p class='info'>" . $_SESSION["mensaje"] . "</p>";
        unset($_SESSION["mensaje"]);
    }
    ?>

    <?php
    if (isset($_POST["btnBorrar"])) {
    ?>
        <h2>Borrar un libro</h2>
        <form action="index.php" method="post">
            <p>¿Estas seguro de querer borrar el libro <?= $_POST["btnBorrar"] ?> ?</p>
            <button type="submit">Volver</button>
            <button type="submit" name="btnContBorrar" value="<?= $_POST["btnBorrar"] ?>">Continuar</button>
        </form>
    <?php
    } else if (isset($_POST["btnEditar"]) || (isset($_POST["btnContEditar"]) && $error_form)) {
        $ref = $_POST["btnEditar"] ?? $_POST["btnContEditar"];

        foreach ($libros as $libro) {
            if ($libro["referencia"] == $ref) {
                $libro_editar = $libro;
            }
        }

        $titulo = $_POST["titulo"] ?? $libro_editar["titulo"];
        $autor = $_POST["autor"] ?? $libro_editar["autor"];
        $descripcion = $_POST["descripcion"] ?? $libro_editar["descripcion"];
        $precio = $_POST["precio"] ?? $libro_editar["precio"];
    ?>
        <h2>Editar libro</h2>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="referencia">Referencia: </label>
                <input type="text" disabled name="referencia" id="referencia" value="<?= $libro_editar["referencia"] ?>">
            </p>
            <p>
                <label for="titulo">Título: </label>
                <input type="text" name="titulo" id="titulo" value="<?= $titulo ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_titulo) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <p>
                <label for="Autor">Autor: </label>
                <input type="text" name="autor" id="autor" value="<?= $autor ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_autor) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <p>
                <label for="descripcion">Descripción: </label>
                <textarea cols="24" name="descripcion" id="descripcion"><?php if (isset($descripcion))  echo $descripcion ?></textarea>
                <?php
                if (isset($_POST["btnAgregar"]) && $error_autor) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <p>
                <label for="precio">Precio: </label>
                <input type="number" name="precio" id="precio" value="<?= $precio ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_precio) {
                    if ($_POST["precio"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else if (!is_numeric($_POST["precio"])) {
                        echo "<span class='error'>* No es un numero.</span>";
                    } else if ($_POST["precio"] < 0) {
                        echo "<span class='error'>* No puede ser negativo.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="portada">Portada: </label>
                <input type="file" name="portada" id="portada">
                <?php
                if (isset($_FILES["portada"]) && $error_portada) {
                    if ($_FILES["portada"]["error"]) {
                        echo "<span class='error'>* Error en la subida.</span>";
                    } else if (!tiene_ext($_FILES["portada"]["tmp_name"])) {
                        echo "<span class='error'>* No tiene extension.</span>";
                    } else if ($_FILES["portada"]["size"] > (500 * 1024)) {
                        echo "<span class='error'>* Tamaño exedido (500 KB)</span>";
                    }
                }
                ?>
            </p>
            <button type="submit">Volver</button>
            <button type="submit" name="btnContEditar" value="<?= $libro_editar["referencia"] ?>">Editar</button>
        </form>
    <?php
    } else {
    ?>
        <h2>Agregar un nuevo libro</h2>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <p>
                <label for="referencia">Referencia: </label>
                <input type="text" name="referencia" id="referencia" value="<?php if (isset($_POST["referencia"]))  echo $_POST["referencia"] ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_ref) {
                    if ($_POST["referencia"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else if (!is_numeric($_POST["referencia"])) {
                        echo "<span class='error'>* No es un numero.</span>";
                    } else if ($_POST["referencia"] < 0) {
                        echo "<span class='error'>* No puede ser negativo.</span>";
                    } else {
                        echo "<span class='error'>* Campo repetido.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="titulo">Título: </label>
                <input type="text" name="titulo" id="titulo" value="<?php if (isset($_POST["titulo"]))  echo $_POST["titulo"] ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_titulo) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <p>
                <label for="Autor">Autor: </label>
                <input type="text" name="autor" id="autor" value="<?php if (isset($_POST["autor"]))  echo $_POST["autor"] ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_autor) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <p>
                <label for="descripcion">Descripción: </label>
                <textarea cols="24" name="descripcion" id="descripcion"><?php if (isset($_POST["descripcion"]))  echo $_POST["descripcion"] ?></textarea>
                <?php
                if (isset($_POST["btnAgregar"]) && $error_autor) {
                    echo "<span class='error'>* Campo obligatorio.</span>";
                }
                ?>
            </p>
            <p>
                <label for="precio">Precio: </label>
                <input type="number" name="precio" id="precio" value="<?php if (isset($_POST["precio"]))  echo $_POST["precio"] ?>">
                <?php
                if (isset($_POST["btnAgregar"]) && $error_precio) {
                    if ($_POST["precio"] == "") {
                        echo "<span class='error'>* Campo obligatorio.</span>";
                    } else if (!is_numeric($_POST["precio"])) {
                        echo "<span class='error'>* No es un numero.</span>";
                    } else if ($_POST["precio"] < 0) {
                        echo "<span class='error'>* No puede ser negativo.</span>";
                    }
                }
                ?>
            </p>
            <p>
                <label for="portada">Portada: </label>
                <input type="file" name="portada" id="portada">
                <?php
                if (isset($_FILES["portada"]) && $error_portada) {
                    if ($_FILES["portada"]["error"]) {
                        echo "<span class='error'>* Error en la subida.</span>";
                    } else if (!tiene_ext($_FILES["portada"]["tmp_name"])) {
                        echo "<span class='error'>* No tiene extension.</span>";
                    } else if ($_FILES["portada"]["size"] > (500 * 1024)) {
                        echo "<span class='error'>* Tamaño exedido (500 KB)</span>";
                    }
                }
                ?>
            </p>
            <button type="submit" name="btnAgregar">Agregar</button>
        </form>
    <?php
    }
    ?>
</body>

</html>