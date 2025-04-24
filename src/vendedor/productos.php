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
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Gestión de Productos</h2>
    <p>Aquí podrás consultar y modificar productos disponibles.</p>
    
    <div class="d-flex gap-2 mt-3">
        <a href="home.php" class="btn btn-secondary">Volver</a>
        <a href="insertar_producto.php" class="btn btn-success">Agregar Producto</a>
    </div>
</div>
</body>
</html>