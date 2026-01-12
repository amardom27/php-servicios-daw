<?php
function login($usuario, $clave) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select id_usuario from usuarios where usuario = ? and clave = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    // Comprobacion de los resultados en caso de que sea necesario
    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra en la base de datos.";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function logueado($id_usuario) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from usuarios where id_usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$id_usuario]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    // Comprobacion de los resultados en caso de que sea necesario
    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra en la base de datos.";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function productos() {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from producto";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    // Comprobacion de los resultados en caso de que sea necesario
    if ($sentencia->rowCount() > 0) {
        $respuesta["productos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No ha productos en la base de datos";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}
function detalle_producto($codigo) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select producto.*, familia.nombre as nom_familia from producto join familia on producto.familia = familia.cod where producto.cod = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$codigo]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    // Comprobacion de los resultados en caso de que sea necesario
    if ($sentencia->rowCount() > 0) {
        $respuesta["producto"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No hay un producto con ese codigo en la BD.";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function familias() {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from familia";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    // Comprobacion de los resultados en caso de que sea necesario
    if ($sentencia->rowCount() > 0) {
        $respuesta["familias"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No ha familias en la base de datos";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function nuevo_producto($datos) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "insert into `producto`(`cod`, `nombre`, `nombre_corto`, `descripcion`, `PVP`, `familia`) values (?, ?, ?, ?, ?, ?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "El producto " . $datos[2] . " se ha insertado correctamente.";

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function editar_producto($datos) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "update `producto` set `cod`= ?,`nombre`= ? ,`nombre_corto`= ?,`descripcion`= ?,`PVP`= ?,`familia`= ? where cod = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "El producto " . $datos[2] . " se ha actualizado correctamente.";

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function borrar_producto($codigo) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "delete from producto where cod = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$codigo]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "El producto " . $codigo . " se ha borrado correctamente.";

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

// Función para comprobar si un valor ya existe en una columna de una tabla
// antes de insertar un nuevo registro.
//
// $tabla: nombre de la tabla
// $columna: nombre de la columna que debe ser única
// $valor: valor que queremos comprobar
//
// Devuelve un array con "repetido" => true si ya existe, false si no, o "error" si falla
function repetido_ins($tabla, $columna, $valor) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from " . $tabla . " where " . $columna . " = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$valor]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["repetido"] = true;
    } else {
        $respuesta["repetido"] = false;
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

// Función para comprobar si un valor ya existe en una columna de una tabla
// al editar un registro, excluyendo el propio registro actual.
//
// $tabla: nombre de la tabla
// $columna: columna que debe ser única
// $valor: valor que queremos comprobar
// $columna_id: columna identificadora del registro (clave primaria)
// $valor_id: valor del registro que estamos editando (clave primaria)
//
// Devuelve un array con "repetido" => true si existe en otro registro, false si es válido, o "error" si falla
function repetido_editar($tabla, $columna, $valor, $columna_id, $valor_id) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "Error en la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from " . $tabla . " where " . $columna . " = ? and " . $columna_id . " <> ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$valor, $valor_id]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["repetido"] = true;
    } else {
        $respuesta["repetido"] = false;
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}
