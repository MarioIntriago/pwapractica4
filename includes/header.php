<?php
$base = $base ?? "";
$titulo = $titulo ?? "Sistema de Calificaciones";
$activo = $activo ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo limpiar($titulo); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $base; ?>css/estilos.css">
</head>
<body>

<header class="topbar">
    <div class="brand">
        <span class="brand-icon">🎓</span>
        <div>
            <strong>Sistema de Calificaciones</strong>
            <small>HTML · CSS · JS · PHP · MySQL</small>
        </div>
    </div>

    <?php if (isset($_SESSION["usuario_id"])): ?>
        <div class="user-box">
            <span><?php echo limpiar($_SESSION["nombre"]); ?></span>
            <small><?php echo nombre_rol($_SESSION["rol"]); ?></small>
            <a class="btn btn-light" href="<?php echo $base; ?>logout.php">Salir</a>
        </div>
    <?php endif; ?>
</header>

<?php if (isset($_SESSION["usuario_id"])): ?>
<nav class="sidebar">
    <?php if (intval($_SESSION["rol"]) === 1): ?>
        <a class="<?php echo $activo === 'dashboard' ? 'active' : ''; ?>" href="<?php echo $base; ?>docente/dashboard.php">Inicio</a>
        <a class="<?php echo $activo === 'estudiantes' ? 'active' : ''; ?>" href="<?php echo $base; ?>docente/estudiantes.php">Estudiantes</a>
        <a class="<?php echo $activo === 'lugares' ? 'active' : ''; ?>" href="<?php echo $base; ?>docente/lugares.php">Lugares</a>
        <a class="<?php echo $activo === 'asignaturas' ? 'active' : ''; ?>" href="<?php echo $base; ?>docente/asignaturas.php">Asignaturas</a>
        <a class="<?php echo $activo === 'asignar' ? 'active' : ''; ?>" href="<?php echo $base; ?>docente/asignar.php">Asignar</a>
        <a class="<?php echo $activo === 'notas' ? 'active' : ''; ?>" href="<?php echo $base; ?>docente/notas.php">Notas</a>
    <?php else: ?>
        <a class="<?php echo $activo === 'dashboard' ? 'active' : ''; ?>" href="<?php echo $base; ?>estudiante/dashboard.php">Inicio</a>
        <a class="<?php echo $activo === 'notas' ? 'active' : ''; ?>" href="<?php echo $base; ?>estudiante/notas.php">Mis notas</a>
    <?php endif; ?>
</nav>
<?php endif; ?>

<main class="<?php echo isset($_SESSION["usuario_id"]) ? 'content' : 'content-login'; ?>">
