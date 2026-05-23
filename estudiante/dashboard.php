<?php
$base = "../";
$titulo = "Panel del estudiante";
$activo = "dashboard";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(2);

$usuario_id = intval($_SESSION["usuario_id"]);

$sql = "SELECT COUNT(*) AS total FROM notas WHERE usuario_id=? AND fecha_eliminacion IS NULL";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$total_notas = mysqli_fetch_assoc($res)["total"] ?? 0;

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Bienvenido, <?php echo limpiar($_SESSION["nombre"]); ?></h1>
    <p>En este panel puedes consultar tus calificaciones registradas por el docente.</p>
</section>

<section class="cards-grid">
    <a class="stat-card" href="notas.php">
        <span>📝</span>
        <strong><?php echo $total_notas; ?></strong>
        <small>Notas disponibles</small>
    </a>
</section>

<section class="panel">
    <h2>Información</h2>
    <p>Solo puedes visualizar tus propias notas. No tienes permisos para modificar registros.</p>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
