<?php
const NOMBRE_BD = "bd_tienda";

function obtener_productos() {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from producto";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["productos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtener_producto_por_id($cod) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select producto.*, familia.nombre as nombre_familia from producto join familia on producto.familia = familia.cod where producto.cod = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod]);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() <= 0) {
        $respuesta["mensaje"] = "El producto no se encuentra en la BD";
    } else {
        $respuesta["producto"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function insertar_producto($datos) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "insert into producto (`cod`, `nombre`, `nombre_corto`, `descripcion`, `PVP`, `familia`) values (?, ?, ?, ?, ?, ?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "El producto " . $datos[2] . " se ha insertado correctamente";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function actualizar_producto($datos) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "update producto set cod = ?, nombre = ?, nombre_corto = ?, descripcion = ?, PVP = ?, familia = ? where cod = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "El producto " . $datos[2] . " se ha actualizado correctamente";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function borrar_producto_por_id($cod) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "delete from producto where cod = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$cod]);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "El producto con id " . $cod . " se ha borrado correctamente";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtener_familias() {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from familia";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute();
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["familias"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function repetido_insertar($tabla, $columna, $valor) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . " = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$valor]);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["repetido"] = $sentencia->rowCount() > 0;

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function repetido_actualizar($tabla, $columna, $valor, $columna_id, $valor_id) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error_bd"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . " = ? and " . $columna_id . " <> ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$valor, $valor_id]);
    } catch (PDOException $e) {
        // Hay que cerrar la sentencia tambien (siempre !!)
        $sentencia = null;
        $conexion = null;
        $respuesta["error_bd"] = "En la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["repetido"] = $sentencia->rowCount() > 0;

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}
