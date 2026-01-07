<?php
session_name("actividad_3");
session_start();

require "src/func_ctes.php";

if (isset($_POST["btnSalirSesion"])) {
    session_destroy();

    header("Location: index.php");
    exit;
}

if (isset($_SESSION["logueado"])) {
    require "vistas/control_seg.php";

    if ($usuario["tipo"] == "admin") {
        require "vistas/vista_admin.php";
    } else {
        require "vistas/vista_normal.php";
    }
} else {
    require "vistas/vista_home.php";
}
