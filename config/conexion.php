<?php
// =============================================
// CONEXIÓN A MYSQL
// Local con Laragon/XAMPP:
// host: localhost
// usuario: root
// contraseña: vacía
// base de datos: 01_calif
//
// En InfinityFree debes reemplazar estos datos
// por los que te da el panel de MySQL.
// =============================================

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "DeltaUBE2026";
$DB_NAME = "01_calif";

$conexion = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");
?>
