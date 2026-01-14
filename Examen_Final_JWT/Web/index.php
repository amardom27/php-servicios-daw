<?php
session_name("Examen2_25_26");
session_start();

require "src/funciones_ctes.php";

//Aquí pondría el código para cerra la Sesión
if (isset($_POST["btnCerrarSesion"])) {
    session_destroy();
    header("Location:index.php");
    exit;
}

if (isset($_SESSION["token"])) {
    echo $_SESSION["token"];
    require "src/control_seguridad.php";
    if ($datos_usu_log["tipo"] == "admin")
        require "vistas/vista_admin.php";
    else
        require "vistas/vista_normal.php";
} else
    require "vistas/vista_home.php";
