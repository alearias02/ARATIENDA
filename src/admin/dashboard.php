<?php
session_start();
require_once "../../DAL/conexion.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol_id'] != 1) {
    header("Location: ../../userSrc/login.php");
    exit();
}

$conn = conectar();

// Puedes agregar más consultas aquí para cargar datos si es necesario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Bienvenido, <?php echo $_SESSION['usuario']['user_name']; ?> (Administrador)</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Usuarios</h5>
                        <p class="card-text">Administra los usuarios del sistema.</p>
                        <a href="usuarios.php" class="btn btn-light">Ver Usuarios</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Gestión de Productos</h5>
                        <p class="card-text">Agrega o edita productos disponibles.</p>
                        <a href="productos.php" class="btn btn-light">Ver Productos</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Facturación</h5>
                        <p class="card-text">Consulta y genera facturas.</p>
                        <a href="facturas.php" class="btn btn-light">Ver Facturas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="../../userSrc/logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
    </div>
</body>
</html>
