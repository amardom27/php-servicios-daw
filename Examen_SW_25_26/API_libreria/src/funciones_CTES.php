<?php



define("SERVIDOR_BD", "localhost");
define("USUARIO_BD", "jose");
define("CLAVE_BD", "josefa");
define("NOMBRE_BD", "bd_libreria_exam");





function login($datos_login) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from usuarios where lector=? and clave=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos_login);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0)
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    else
        $respuesta["mensaje"] = "El usuario no se encuentra en la BD";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}


function logueado($id_usuario) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from usuarios where id_usuario=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$id_usuario]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0)
        $respuesta["usuario"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    else
        $respuesta["mensaje_baneo"] = "El usuario no se encuentra en la BD";

    $sentencia = null;
    $conexion = null;
    return $respuesta;
}

function obtener_libros() {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from libros";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["libros"] = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No hay libros en la base de datos";
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function obtener_libro($ref) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from libros where referencia=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$ref]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    if ($sentencia->rowCount() > 0) {
        $respuesta["libro"] = $sentencia->fetch(PDO::FETCH_ASSOC);
    } else {
        $respuesta["mensaje"] = "No hay libros en la base de datos";
    }

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function crear_libro($datos_libro) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "insert into `libros`(`referencia`, `titulo`, `autor`, `descripcion`, `precio`) values (?, ?, ?, ?, ?)";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos_libro);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "Libro insertado correctamente en la BD.";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function borrar_libro($ref) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "delete from libros where referencia = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$ref]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "Libro borrado correctamente en la BD.";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function actualizar_libro($datos_libro) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "update libros set `titulo`=?,`autor`=?,`descripcion`=?,`precio`=? where referencia=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute($datos_libro);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "Libro actualizado correctamente en la BD.";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function actualizar_portada($portada, $ref) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "update libros set `portada`=? where referencia=?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$portada, $ref]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["mensaje"] = "Portada cambiada correctamente en la BD.";

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function repetido_insertar($tabla, $columna, $valor) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from " . $tabla . " where " . $columna . " = ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$valor]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["repetido"] = $sentencia->rowCount() > 0;

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}

function repetido_editar($tabla, $columna, $valor, $columna_id, $valor_id) {
    try {
        $conexion = new PDO("mysql:host=" . SERVIDOR_BD . ";dbname=" . NOMBRE_BD, USUARIO_BD, CLAVE_BD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    } catch (PDOException $e) {
        $respuesta["error"] = "No he podido conectarse a la base de batos: " . $e->getMessage();
        return $respuesta;
    }

    try {
        $consulta = "select * from " . $tabla . " where " . $columna . " = ? and " . $columna_id . " <> ?";
        $sentencia = $conexion->prepare($consulta);
        $sentencia->execute([$valor, $valor_id]);
    } catch (PDOException $e) {
        $sentencia = null;
        $conexion = null;
        $respuesta["error"] = "No he podido realizarse la consulta: " . $e->getMessage();
        return $respuesta;
    }

    $respuesta["repetido"] = $sentencia->rowCount() > 0;

    $sentencia = null;
    $conexion = null;

    return $respuesta;
}
