<?php
require_once __DIR__ . "/includes/auth.php";

if (esta_logueado()) {
    redirigir_por_rol();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $contrasena = trim($_POST["contrasena"] ?? "");

    if ($email === "" || $contrasena === "") {
        $error = "Ingrese email y contraseña.";
    } else {
        $sql = "SELECT id, nombre, email, rol 
                FROM usuarios 
                WHERE email = ? 
                  AND contrasena = ? 
                  AND fecha_eliminacion IS NULL
                LIMIT 1";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $contrasena);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($usuario = mysqli_fetch_assoc($resultado)) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["email"] = $usuario["email"];
            $_SESSION["rol"] = $usuario["rol"];
            redirigir_por_rol();
        } else {
            $error = "Credenciales incorrectas o usuario eliminado.";
        }
    }
}

$base = "";
$titulo = "Iniciar sesión";
include __DIR__ . "/includes/header.php";
?>

<section class="login-card">
    <div class="login-info">
        <h1>Sistema de Calificaciones</h1>
        <p>Acceso para docentes y estudiantes. El docente gestiona estudiantes, lugares, asignaturas y notas; el estudiante visualiza sus calificaciones.</p>

        <div class="demo-box">
            <strong>Usuarios de prueba</strong>
            <p><b>Docente:</b> docente@gmail.com / 1234</p>
            <p><b>Estudiante:</b> mario@gmail.com / 1234</p>
        </div>
    </div>

    <form class="form-card" method="POST">
        <h2>Iniciar sesión</h2>

        <?php if ($error): ?>
            <div class="alert error"><?php echo limpiar($error); ?></div>
        <?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" placeholder="correo@ejemplo.com" required>

        <label>Contraseña</label>
        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <button class="btn btn-primary" type="submit">Entrar</button>
    </form>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>
