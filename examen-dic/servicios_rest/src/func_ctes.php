<?php
function login($usuario, $clave) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from usuarios where usuario = ? and clave = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$usuario, $clave]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD.";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function logueado($id) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from usuarios where id_usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$id]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD.";
    }

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function horario_profesor_id($id_usuario) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select dia, hora, grupos.nombre as grupo, aulas.nombre as aula
        from horario_lectivo
            join grupos on horario_lectivo.grupo = grupos.id_grupo
            join aulas on horario_lectivo.aula = aulas.id_aula
        where horario_lectivo.usuario = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$id_usuario]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["horario"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function horario_profesor_grupo($id_grupo) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select dia, hora, usuarios.usuario as usuario, aulas.nombre as aula
        from horario_lectivo
            join aulas on horario_lectivo.aula = aulas.id_aula
            join usuarios on horario_lectivo.usuario = usuarios.id_usuario
        where horario_lectivo.grupo = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$id_grupo]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["horario"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function grupos() {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from grupos";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["grupos"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function horarios_profesores_libres($dia, $hora, $id_grupo) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . NOMBRE_BD, USUARIO, CLAVE, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "En la conexion a la BD: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select usuarios.id_usuario, usuarios.usuario, aulas.nombre as aula
        from horario_lectivo
            join usuarios on horario_lectivo.usuario = usuarios.id_usuario
            join aulas on horario_lectivo.aula = aulas.id_aula
        where dia = ? and hora = ? and grupo = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$dia, $hora, $id_grupo]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;

        $respuesta["error"] = "Error en la consulta a la BD: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["profesores_libres"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    // NO OLVIDAR CERRAR
    $sentencia = null;
    $conexion = null;

    return $respuesta;
}
