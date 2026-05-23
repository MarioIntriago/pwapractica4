<?php
$base = "../";
$titulo = "Gestión de asignaturas";
$activo = "asignaturas";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(1);

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {
        $nombre = trim($_POST["nombre"] ?? "");
        $obs = trim($_POST["obs"] ?? "");

        if ($nombre === "") {
            $error = "Ingrese el nombre de la asignatura.";
        } else {
            $sql = "INSERT INTO asignaturas (nombre, obs, usuario_id_creacion, fecha_creacion, hora_creacion)
                    VALUES (?, ?, ?, NOW(), CURTIME())";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $nombre, $obs, $_SESSION["usuario_id"]);

            if (mysqli_stmt_execute($stmt)) $mensaje = "Asignatura registrada.";
            else $error = "No se pudo registrar.";
        }
    }

    if ($accion === "actualizar") {
        $id = intval($_POST["id"] ?? 0);
        $nombre = trim($_POST["nombre"] ?? "");
        $obs = trim($_POST["obs"] ?? "");

        $sql = "UPDATE asignaturas 
                SET nombre=?, obs=?, usuario_id_actualizacion=?, fecha_actualizacion=NOW(), hora_actualizacion=CURTIME()
                WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $nombre, $obs, $_SESSION["usuario_id"], $id);

        if (mysqli_stmt_execute($stmt)) $mensaje = "Asignatura actualizada.";
        else $error = "No se pudo actualizar.";
    }

    if ($accion === "eliminar") {
        $id = intval($_POST["id"] ?? 0);

        $sql = "UPDATE asignaturas 
                SET usuario_id_eliminacion=?, fecha_eliminacion=NOW(), hora_eliminacion=CURTIME()
                WHERE id=?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION["usuario_id"], $id);

        if (mysqli_stmt_execute($stmt)) $mensaje = "Asignatura eliminada lógicamente.";
        else $error = "No se pudo eliminar.";
    }
}

$asignaturas = mysqli_query($conexion, "SELECT * FROM asignaturas WHERE fecha_eliminacion IS NULL ORDER BY id DESC");

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Asignaturas</h1>
    <p>Registra las materias que imparte el docente.</p>
</section>

<?php if ($mensaje): ?><div class="alert success"><?php echo limpiar($mensaje); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?php echo limpiar($error); ?></div><?php endif; ?>

<section class="panel">
    <h2>Nueva asignatura</h2>
    <form method="POST" class="form-grid">
        <input type="hidden" name="accion" value="crear">

        <div>
            <label>Nombre</label>
            <input type="text" name="nombre" placeholder="Ej: Programación Web" required>
        </div>

        <div>
            <label>Observación</label>
            <input type="text" name="obs" placeholder="Opcional">
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
</section>

<section class="panel">
    <h2>Listado de asignaturas</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asignatura</th>
                    <th>Observación</th>
                    <th>Creación</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($fila = mysqli_fetch_assoc($asignaturas)): ?>
                <tr>
                    <form method="POST">
                        <td><?php echo $fila["id"]; ?></td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">
                            <input type="text" name="nombre" value="<?php echo limpiar($fila["nombre"]); ?>" required>
                        </td>
                        <td><input type="text" name="obs" value="<?php echo limpiar($fila["obs"]); ?>"></td>
                        <td><?php echo limpiar($fila["fecha_creacion"]); ?></td>
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
