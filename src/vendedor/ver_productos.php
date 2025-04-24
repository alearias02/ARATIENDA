<?php
session_start();
require_once "../../DAL/conexion.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol_id'] != 2) {
    header("Location: ../../userSrc/login.php");
    exit();
}

$conn = conectar();
$sql = "SELECT ID_PRODUCTO, NOMBRE_PRODUCTO, PRECIO FROM FIDE_ARATIENDA.FIDE_PRODUCTOS_TB ORDER BY ID_PRODUCTO DESC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Lista de Productos</h2>
    <a href="productos.php" class="btn btn-secondary mb-3">Volver</a>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = oci_fetch_assoc($stmt)): ?>
                <tr>
                    <td><?= $row['ID_PRODUCTO'] ?></td>
                    <td><?= $row['NOMBRE_PRODUCTO'] ?></td>
                    <td><?= number_format($row['PRECIO'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php
oci_free_statement($stmt);
oci_close($conn);
?>