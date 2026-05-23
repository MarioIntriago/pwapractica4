<?php
$base = "../";
$titulo = "Panel del docente";
$activo = "dashboard";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(1);

function contar($conexion, $tabla, $condicion = "fecha_eliminacion IS NULL") {
    $sql = "SELECT COUNT(*) AS total FROM $tabla WHERE $condicion";
    $res = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row["total"] ?? 0;
}

$total_estudiantes = contar($conexion, "usuarios", "rol = 2 AND fecha_eliminacion IS NULL");
$total_lugares = contar($conexion, "lugares");
$total_asignaturas = contar($conexion, "asignaturas");
$total_notas = contar($conexion, "notas");

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Panel del docente</h1>
    <p>Desde aquí puedes administrar estudiantes, lugares, asignaturas, asignaciones y notas.</p>
</section>

<section class="cards-grid">
    <a class="stat-card" href="estudiantes.php">
        <span>👥</span>
        <strong><?php echo $total_estudiantes; ?></strong>
        <small>Estudiantes</small>
    </a>

    <a class="stat-card" href="lugares.php">
        <span>🏫</span>
        <strong><?php echo $total_lugares; ?></strong>
        <small>Lugares educativos</small>
    </a>

    <a class="stat-card" href="asignaturas.php">
        <span>📚</span>
        <strong><?php echo $total_asignaturas; ?></strong>
        <small>Asignaturas</small>
    </a>

    <a class="stat-card" href="notas.php">
        <span>📝</span>
        <strong><?php echo $total_notas; ?></strong>
        <small>Notas registradas</small>
    </a>
</section>

<section class="panel">
    <h2>Funcionamiento solicitado</h2>
    <ul class="list">
        <li>El docente registra estudiantes con nombres, apellidos y email.</li>
        <li>El docente crea lugares educativos y asignaturas.</li>
        <li>El docente asigna estudiantes a lugares y asignaturas.</li>
        <li>El docente ingresa nota de teoría y nota de práctica.</li>
        <li>El estudiante accede al sistema y visualiza únicamente sus notas.</li>
    </ul>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
