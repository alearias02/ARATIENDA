<?php
session_start();
require_once "../../DAL/conexion.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol_id'] != 2) {
    header("Location: ../../userSrc/login.php");
    exit();
}

$conn = conectar();
$sql = "
SELECT p.NOMBRE_PRODUCTO, t.NOMBRE_TIENDA, i.DISPONIBLE_EN_BODEGA
FROM FIDE_ARATIENDA.FIDE_INVENTARIO_TB i
JOIN FIDE_ARATIENDA.FIDE_PRODUCTOS_TB p ON i.ID_PRODUCTO = p.ID_PRODUCTO
JOIN FIDE_ARATIENDA.FIDE_BODEGAS_TB b ON i.ID_BODEGA = b.ID_BODEGA
JOIN FIDE_ARATIENDA.FIDE_TIENDAS_TB t ON b.ID_TIENDA = t.ID_TIENDA
ORDER BY p.NOMBRE_PRODUCTO, t.NOMBRE_TIENDA
";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidad de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Disponibilidad en Tiendas</h2>
    <a href="productos.php" class="btn btn-secondary mb-3">Volver</a>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th>Tienda</th>
                <th>Cantidad Disponible</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = oci_fetch_assoc($stmt)): ?>
                <tr>
                    <td><?= $row['NOMBRE_PRODUCTO'] ?></td>
                    <td><?= $row['NOMBRE_TIENDA'] ?></td>
                    <td><?= $row['DISPONIBLE_EN_BODEGA'] ?></td>
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