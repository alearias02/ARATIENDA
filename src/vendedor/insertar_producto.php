<?php
session_start();
require_once "../../DAL/conexion.php";
require_once "../../include/functions/recoge.php";

$conn = conectar();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = recogePost('nombre');
    $precio = recogePost('precio');

    $sql = "INSERT INTO FIDE_PRODUCTOS_TB (id_producto, nombre_producto, precio) 
            VALUES (FIDE_SEQ_PRODUCTOS_ID.NEXTVAL, :nombre, :precio)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":precio", $precio);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $mensaje = "Producto agregado correctamente.";
    } else {
        oci_rollback($conn);
        $mensaje = "Error al agregar producto.";
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h3>Agregar Nuevo Producto</h3>
    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre del producto</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="home.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>