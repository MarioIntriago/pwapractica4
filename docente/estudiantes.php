<?php
$base = "../";
$titulo = "Gestión de estudiantes";
$activo = "estudiantes";
require_once __DIR__ . "/../includes/auth.php";
requerir_rol(1);

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"] ?? "";

    if ($accion === "crear") {
        $nombre = trim($_POST["nombre"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $contrasena = trim($_POST["contrasena"] ?? "");

        if ($nombre === "" || $email === "" || $contrasena === "") {
            $error = "Complete todos los campos.";
        } else {
            $sql = "INSERT INTO usuarios 
                    (nombre, email, rol, contrasena, usuario_id_creacion, fecha_creacion, hora_creacion)
                    VALUES (?, ?, 2, ?, ?, NOW(), CURTIME())";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $nombre, $email, $contrasena, $_SESSION["usuario_id"]);

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Estudiante registrado correctamente.";
            } else {
                $error = "No se pudo registrar. Verifique si el email ya existe.";
            }
        }
    }

    if ($accion === "actualizar") {
        $id = intval($_POST["id"] ?? 0);
        $nombre = trim($_POST["nombre"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $contrasena = trim($_POST["contrasena"] ?? "");

        if ($id <= 0 || $nombre === "" || $email === "") {
            $error = "Datos incompletos para actualizar.";
        } else {
            if ($contrasena !== "") {
                $sql = "UPDATE usuarios SET nombre=?, email=?, contrasena=?, usuario_id_actualizacion=?, fecha_actualizacion=NOW(), hora_actualizacion=CURTIME()
                        WHERE id=? AND rol=2";
                $stmt = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($stmt, "sssii", $nombre, $email, $contrasena, $_SESSION["usuario_id"], $id);
            } else {
                $sql = "UPDATE usuarios SET nombre=?, email=?, usuario_id_actualizacion=?, fecha_actualizacion=NOW(), hora_actualizacion=CURTIME()
                        WHERE id=? AND rol=2";
                $stmt = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($stmt, "ssii", $nombre, $email, $_SESSION["usuario_id"], $id);
            }

            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Estudiante actualizado.";
            } else {
                $error = "No se pudo actualizar el estudiante.";
            }
        }
    }

    if ($accion === "eliminar") {
        $id = intval($_POST["id"] ?? 0);

        $sql = "UPDATE usuarios 
                SET usuario_id_eliminacion=?, fecha_eliminacion=NOW(), hora_eliminacion=CURTIME()
                WHERE id=? AND rol=2";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION["usuario_id"], $id);

        if (mysqli_stmt_execute($stmt)) {
            $mensaje = "Estudiante eliminado lógicamente.";
        } else {
            $error = "No se pudo eliminar el estudiante.";
        }
    }
}

$sql = "SELECT * FROM usuarios WHERE rol=2 AND fecha_eliminacion IS NULL ORDER BY id DESC";
$estudiantes = mysqli_query($conexion, $sql);

include __DIR__ . "/../includes/header.php";
?>

<section class="page-head">
    <h1>Estudiantes</h1>
    <p>Registra los apellidos, nombres, email y contraseña de acceso de cada estudiante.</p>
</section>

<?php if ($mensaje): ?><div class="alert success"><?php echo limpiar($mensaje); ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?php echo limpiar($error); ?></div><?php endif; ?>

<section class="panel">
    <h2>Nuevo estudiante</h2>
    <form method="POST" class="form-grid">
        <input type="hidden" name="accion" value="crear">

        <div>
            <label>Apellidos y nombres</label>
            <input type="text" name="nombre" placeholder="Ej: Intriago Zambrano Mario José" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="estudiante@email.com" required>
        </div>

        <div>
            <label>Contraseña</label>
            <input type="text" name="contrasena" placeholder="1234" required>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Guardar</button>
        </div>
    </form>
</section>

<section class="panel">
    <h2>Listado de estudiantes</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Apellidos y nombres</th>
                    <th>Email</th>
                    <th>Creación</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($estudiantes)): ?>
                    <tr>
                        <form method="POST">
                            <td><?php echo $fila["id"]; ?></td>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $fila["id"]; ?>">
                                <input type="text" name="nombre" value="<?php echo limpiar($fila["nombre"]); ?>" required>
                            </td>
                            <td><input type="email" name="email" value="<?php echo limpiar($fila["email"]); ?>" required></td>
                            <td><?php echo limpiar($fila["fecha_creacion"]); ?></td>
                            <td>
                                <input type="text" name="contrasena" placeholder="Nueva clave opcional">
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
