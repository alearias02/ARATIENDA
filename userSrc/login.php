<?php
session_start();
require_once "../include/functions/recoge.php";
require_once "../DAL/conexion.php";

$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = recogePost('user_name');
    $password = recogePost('password');

    if (empty($correo) || empty($password)) {
        $errores[] = "Usuario y contraseña son obligatorios.";
    } else {
        $conn = conectar();

        $query = "SELECT u.ID_USUARIO, u.NOMBRE, u.CORREO, u.CONTRASENA, u.ID_ROL, r.NOMBRE_ROL AS ROL
                  FROM FIDE_ARATIENDA.FIDE_USUARIOS_TB u 
                  JOIN FIDE_ARATIENDA.FIDE_ROLES_TB r ON u.ID_ROL = r.ID_ROL 
                  WHERE u.CORREO = :correo";

        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ":correo", $correo);
        oci_execute($stmt);

        $mySession = oci_fetch_assoc($stmt);

        if (!$mySession) {
            $errores[] = "Usuario no encontrado.";
        } else {
            if (password_verify($password, $mySession['CONTRASENA']) || $password === $mySession['CONTRASENA']) {
                $_SESSION['usuario'] = [
                    'user_id' => $mySession['ID_USUARIO'],
                    'user_name' => $mySession['NOMBRE'],
                    'email' => $mySession['CORREO'],
                    'rol' => $mySession['ROL'],
                    'rol_id' => $mySession['ID_ROL'],
                    'login' => true
                ];

                // Registrar el rol para debug
                error_log("ROL INICIANDO SESIÓN: " . $mySession['ID_ROL']);

                // Redireccionar según el rol
                // Redireccionar según el rol usando rutas absolutas
                switch ((int) $mySession['ID_ROL']) {
                    case 1:
                        header("Location: /src/admin/dashboard.php");
                        break;
                    case 2:
                        header("Location: /src/vendedor/home.php");
                        break;
                    case 3:
                        header("Location: /src/cliente/inicio.php");
                        break;
                    default:
                        $errores[] = "Rol de usuario no reconocido.";
                }
                exit();
            } else {
                $errores[] = "Contraseña incorrecta.";
            }
        }

        oci_free_statement($stmt);
        oci_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
        <h2 class="mb-4 text-center">Iniciar sesión</h2>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errores as $error): ?>
                    <p class="mb-0"><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="user_name" class="form-label">Correo</label>
                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Ingrese su correo">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Ingrese su contraseña">
            </div>

            <button type="submit" class="btn btn-dark w-100">Iniciar sesión</button>
        </form>

        <div class="mt-3 text-center">
            <p class="mb-1"><a href="register.php">¿No tienes cuenta? Regístrate</a></p>
            <p><a href="recovery.php">¿Olvidaste tu contraseña?</a></p>
        </div>
    </div>

</body>

</html>