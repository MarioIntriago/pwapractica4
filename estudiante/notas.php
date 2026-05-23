<?php
$base = "../";
$titulo = "Mis notas";
$activo = "notas";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(2);

$usuario_id = intval($_SESSION["usuario_id"]);

$sql = "SELECT n.parcial, n.teoria, n.practica, n.obs, n.fecha_creacion,
               a.nombre AS asignatura,
               COALESCE(l.nombre, 'Sin lugar asignado') AS lugar
        FROM notas n
        INNER JOIN asignaturas a ON a.id = n.asignatura_id
        LEFT JOIN asignaturas_estudiante ae 
               ON ae.usuario_id = n.usuario_id 
              AND ae.asignatura_id = n.asignatura_id 
              AND ae.fecha_eliminacion IS NULL
        LEFT JOIN lugares l ON l.id = ae.lugar_id
        WHERE n.usuario_id = ? 
          AND n.fecha_eliminacion IS NULL
        ORDER BY a.nombre, n.parcial";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$notas = mysqli_stmt_get_result($stmt);

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Mis notas</h1>
    <p>Consulta tus calificaciones de teoría y práctica por asignatura.</p>
</section>

<section class="panel">
    <h2>Calificaciones registradas</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Lugar</th>
                    <th>Parcial</th>
                    <th>Teoría</th>
                    <th>Práctica</th>
                    <th>Promedio</th>
                    <th>Observación</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($notas) === 0): ?>
                    <tr>
                        <td colspan="8" class="empty">Todavía no tienes notas registradas.</td>
                    </tr>
                <?php endif; ?>

                <?php while ($fila = mysqli_fetch_assoc($notas)): ?>
                    <?php $promedio = ($fila["teoria"] + $fila["practica"]) / 2; ?>
                    <tr>
                        <td><?php echo limpiar($fila["asignatura"]); ?></td>
                        <td><?php echo limpiar($fila["lugar"]); ?></td>
                        <td>
                            <?php 
                            if (intval($fila["parcial"]) === 1) echo "1er parcial";
                            elseif (intval($fila["parcial"]) === 2) echo "2do parcial";
                            else echo "Mejoramiento";
                            ?>
                        </td>
                        <td><?php echo number_format($fila["teoria"], 2); ?></td>
                        <td><?php echo number_format($fila["practica"], 2); ?></td>
                        <td><span class="badge"><?php echo number_format($promedio, 2); ?></span></td>
                        <td><?php echo limpiar($fila["obs"]); ?></td>
                        <td><?php echo limpiar($fila["fecha_creacion"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
