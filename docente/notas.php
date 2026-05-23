<?php
$base = "../";
$titulo = "Gestión de notas";
$activo = "notas";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(1);

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {
        $usuario_id = intval($_POST["usuario_id"] ?? 0);
        $asignatura_id = intval($_POST["asignatura_id"] ?? 0);
        $parcial = intval($_POST["parcial"] ?? 0);
        $teoria = floatval($_POST["teoria"] ?? 0);
        $practica = floatval($_POST["practica"] ?? 0);
        $obs = trim($_POST["obs"] ?? "");

        if ($usuario_id <= 0 || $asignatura_id <= 0 || $parcial <= 0) {
            $error = "Seleccione estudiante, asignatura y parcial.";
        } else {
            $sql = "INSERT INTO notas
                    (asignatura_id, usuario_id, parcial, teoria, practica, obs, usuario_id_creacion, fecha_creacion, hora_creacion)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), CURTIME())";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "iiiddsi", $asignatura_id, $usuario_id, $parcial, $teoria, $practica, $obs, $_SESSION["usuario_id"]);

            if (mysqli_stmt_execute($stmt)) $mensaje = "Nota registrada correctamente.";
            else $error = "No se pudo registrar la nota.";
        }
    }

    if ($accion === "actualizar") {
        $id = intval($_POST["id"] ?? 0);
        $parcial = intval($_POST["parcial"] ?? 0);
        $teoria = floatval($_POST["teoria"] ?? 0);
        $practica = floatval($_POST["practica"] ?? 0);
        $obs = trim($_POST["obs"] ?? "");

        $sql = "UPDATE notas 
                SET parcial=?, teoria=?, practica=?, obs=?, usuario_id_actualizacion=?, fecha_actualizacion=NOW(), hora_actualizacion=CURTIME()
                WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iddsii", $parcial, $teoria, $practica, $obs, $_SESSION["usuario_id"], $id);

        if (mysqli_stmt_execute($stmt)) $mensaje = "Nota actualizada.";
        else $error = "No se pudo actualizar.";
    }

    if ($accion === "eliminar") {
        $id = intval($_POST["id"] ?? 0);

        $sql = "UPDATE notas
                SET usuario_id_eliminacion=?, fecha_eliminacion=NOW(), hora_eliminacion=CURTIME()
                WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION["usuario_id"], $id);

        if (mysqli_stmt_execute($stmt)) $mensaje = "Nota eliminada lógicamente.";
        else $error = "No se pudo eliminar.";
    }
}

$estudiantes = mysqli_query($conexion, "SELECT id, nombre FROM usuarios WHERE rol=2 AND fecha_eliminacion IS NULL ORDER BY nombre");
$asignaturas = mysqli_query($conexion, "SELECT id, nombre FROM asignaturas WHERE fecha_eliminacion IS NULL ORDER BY nombre");

$sql = "SELECT n.id, n.parcial, n.teoria, n.practica, n.obs, n.fecha_creacion,
               u.nombre AS estudiante, a.nombre AS asignatura
        FROM notas n
        INNER JOIN usuarios u ON u.id = n.usuario_id
        INNER JOIN asignaturas a ON a.id = n.asignatura_id
        WHERE n.fecha_eliminacion IS NULL
        ORDER BY n.id DESC";
$notas = mysqli_query($conexion, $sql);

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Notas</h1>
    <p>Registra la nota de teoría y práctica de cada estudiante.</p>
</section>

<?php if ($mensaje): ?><div class="alert success"><?php echo limpiar($mensaje); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?php echo limpiar($error); ?></div><?php endif; ?>

<section class="panel">
    <h2>Nueva nota</h2>

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
            <label>Asignatura</label>
            <select name="asignatura_id" required>
                <option value="">Seleccione...</option>
                <?php while ($a = mysqli_fetch_assoc($asignaturas)): ?>
                    <option value="<?php echo $a["id"]; ?>"><?php echo limpiar($a["nombre"]); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Parcial</label>
            <select name="parcial" required>
                <option value="1">1er parcial</option>
                <option value="2">2do parcial</option>
                <option value="3">Mejoramiento</option>
            </select>
        </div>

        <div>
            <label>Nota teoría</label>
            <input type="number" name="teoria" min="0" max="10" step="0.01" required>
        </div>

        <div>
            <label>Nota práctica</label>
            <input type="number" name="practica" min="0" max="10" step="0.01" required>
        </div>

        <div>
            <label>Observación</label>
            <input type="text" name="obs" placeholder="Opcional">
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Guardar nota</button>
        </div>
    </form>
</section>

<section class="panel">
    <h2>Notas registradas</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estudiante</th>
                    <th>Asignatura</th>
                    <th>Parcial</th>
                    <th>Teoría</th>
                    <th>Práctica</th>
                    <th>Promedio</th>
                    <th>Observación</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($notas)): ?>
                    <?php $promedio = ($fila["teoria"] + $fila["practica"]) / 2; ?>
                    <tr>
                        <form method="POST">
                            <td><?php echo $fila["id"]; ?></td>
                            <td><?php echo limpiar($fila["estudiante"]); ?></td>
                            <td><?php echo limpiar($fila["asignatura"]); ?></td>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">
                                <select name="parcial">
                                    <option value="1" <?php echo intval($fila["parcial"]) === 1 ? "selected" : ""; ?>>1er</option>
                                    <option value="2" <?php echo intval($fila["parcial"]) === 2 ? "selected" : ""; ?>>2do</option>
                                    <option value="3" <?php echo intval($fila["parcial"]) === 3 ? "selected" : ""; ?>>Mejoramiento</option>
                                </select>
                            </td>
                            <td><input type="number" name="teoria" min="0" max="10" step="0.01" value="<?php echo $fila["teoria"]; ?>"></td>
                            <td><input type="number" name="practica" min="0" max="10" step="0.01" value="<?php echo $fila["practica"]; ?>"></td>
                            <td><span class="badge"><?php echo number_format($promedio, 2); ?></span></td>
                            <td><input type="text" name="obs" value="<?php echo limpiar($fila["obs"]); ?>"></td>
                            <td>
                                <input type="hidden" name="accion" value="actualizar">
                                <button class="btn btn-warning" type="submit">Actualizar</button>
                            </td>
                        </form>
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
