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
    <title>Facturación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Facturación</h2>
    <p>Administra las facturas realizadas a los clientes.</p>
    <a href="home.php" class="btn btn-secondary">Volver</a>
</div>
</body>
</html>