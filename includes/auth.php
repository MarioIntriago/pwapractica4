<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/conexion.php";

function esta_logueado() {
    return isset($_SESSION["usuario_id"]);
}

function requerir_login() {
    if (!esta_logueado()) {
        header("Location: ../login.php");
        exit;
    }
}

function requerir_rol($rol) {
    requerir_login();

    if (!isset($_SESSION["rol"]) || intval($_SESSION["rol"]) !== intval($rol)) {
        header("Location: ../index.php");
        exit;
    }
}

function nombre_rol($rol) {
    return intval($rol) === 1 ? "Docente" : "Estudiante";
}

function limpiar($valor) {
    return htmlspecialchars(trim($valor ?? ""), ENT_QUOTES, "UTF-8");
}

function redirigir_por_rol() {
    if (!isset($_SESSION["rol"])) {
        header("Location: login.php");
        exit;
    }

    if (intval($_SESSION["rol"]) === 1) {
        header("Location: docente/dashboard.php");
        exit;
    }

    if (intval($_SESSION["rol"]) === 2) {
        header("Location: estudiante/dashboard.php");
        exit;
    }

    header("Location: login.php");
    exit;
}
?>
