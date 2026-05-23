<?php
$base = "../";
$titulo = "Asignar estudiantes";
$activo = "asignar";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(1);

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {
        $lugar_id = intval($_POST["lugar_id"] ?? 0);
        $asignatura_id = intval($_POST["asignatura_id"] ?? 0);
        $usuario_id = intval($_POST["usuario_id"] ?? 0);

        if ($lugar_id <= 0 || $asignatura_id <= 0 || $usuario_id <= 0) {
            $error = "Seleccione lugar, asignatura y estudiante.";
        } else {
            $sql = "INSERT INTO asignaturas_estudiante 
                    (lugar_id, asignatura_id, usuario_id, usuario_id_creacion, fecha_creacion, hora_creacion)
                    VALUES (?, ?, ?, ?, NOW(), CURTIME())";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "iiii", $lugar_id, $asignatura_id, $usuario_id, $_SESSION["usuario_id"]);

            if (mysqli_stmt_execute($stmt)) $mensaje = "Asignación registrada.";
            else $error = "No se pudo registrar la asignación.";
        }
    }

    if ($accion === "eliminar") {
        $id = intval($_POST["id"] ?? 0);

        $sql = "UPDATE asignaturas_estudiante 
                SET usuario_id_eliminacion=?, fecha_eliminacion=NOW(), hora_eliminacion=CURTIME()
                WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION["usuario_id"], $id);

        if (mysqli_stmt_execute($stmt)) $mensaje = "Asignación eliminada lógicamente.";
        else $error = "No se pudo eliminar.";
    }
}

$estudiantes = mysqli_query($conexion, "SELECT id, nombre FROM usuarios WHERE rol=2 AND fecha_eliminacion IS NULL ORDER BY nombre");
$lugares = mysqli_query($conexion, "SELECT id, nombre FROM lugares WHERE fecha_eliminacion IS NULL ORDER BY nombre");
$asignaturas = mysqli_query($conexion, "SELECT id, nombre FROM asignaturas WHERE fecha_eliminacion IS NULL ORDER BY nombre");

$sql = "SELECT ae.id, u.nombre AS estudiante, l.nombre AS lugar, a.nombre AS asignatura, ae.fecha_creacion
        FROM asignaturas_estudiante ae
        INNER JOIN usuarios u ON u.id = ae.usuario_id
        INNER JOIN lugares l ON l.id = ae.lugar_id
        INNER JOIN asignaturas a ON a.id = ae.asignatura_id
        WHERE ae.fecha_eliminacion IS NULL
        ORDER BY ae.id DESC";
$asignaciones = mysqli_query($conexion, $sql);

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Asignar estudiantes</h1>
    <p>Relaciona cada estudiante con el lugar educativo y la asignatura correspondiente.</p>
</section>

<?php if ($mensaje): ?><div class="alert success"><?php echo limpiar($mensaje); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?php echo limpiar($error); ?></div><?php endif; ?>

<section class="panel">
    <h2>Nueva asignación</h2>
    <form method="POST" class="form-grid">
        <input type="hidden" name="accion" value="crear">

        <div>
            <label>Estudiante</label>
            <select name="usuario_id" required>
                <option value="">Seleccione...</option>
                <?php while ($e = mysqli_fetch_assoc($estudiantes)): ?>
                    <option value="<?php echo $e["id"]; ?>"><?php echo limpiar($e["nombre"]); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Lugar</label>
            <select name="lugar_id" required>
                <option value="">Seleccione...</option>
                <?php while ($l = mysqli_fetch_assoc($lugares)): ?>
                    <option value="<?php echo $l["id"]; ?>"><?php echo limpiar($l["nombre"]); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Asignatura</label>
            <select name="asignatura_id" required>
                <option value="">Seleccione...</option>
                <?php while ($a = mysqli_fetch_assoc($asignaturas)): ?>
                    <option value="<?php echo $a["id"]; ?>"><?php echo limpiar($a["nombre"]); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Guardar asignación</button>
        </div>
    </form>
</section>

<section class="panel">
    <h2>Asignaciones registradas</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estudiante</th>
                    <th>Lugar</th>
                    <th>Asignatura</th>
                    <th>Fecha</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($asignaciones)): ?>
                    <tr>
                        <td><?php echo $fila["id"]; ?></td>
                        <td><?php echo limpiar($fila["estudiante"]); ?></td>
                        <td><?php echo limpiar($fila["lugar"]); ?></td>
                        <td><?php echo limpiar($fila["asignatura"]); ?></td>
                        <td><?php echo limpiar($fila["fecha_creacion"]); ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirmarEliminacion();">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">
                                <button class="btn btn-danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
