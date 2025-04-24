<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol_id'] != 2) {
    header("Location: ../../userSrc/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Gestión de Productos</h2>
    <p>Aquí podrás consultar, agregar y visualizar la disponibilidad de productos.</p>

    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="home.php" class="btn btn-secondary">Volver</a>
        <a href="insertar_producto.php" class="btn btn-success">Agregar Nuevo Producto</a>
        <a href="ver_productos.php" class="btn btn-primary">Ver Todos los Productos</a>
        <a href="ver_disponibilidad.php" class="btn btn-info">Ver Disponibilidad en Tiendas</a>
    </div>
</div>
</body>
</html>