<?php
require_once __DIR__ . "/includes/auth.php";

if (esta_logueado()) {
    redirigir_por_rol();
}

header("Location: login.php");
exit;
?>
