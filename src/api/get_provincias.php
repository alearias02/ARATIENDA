<?php
require_once '../../DAL/conexion.php';
header('Content-Type: application/json');

$conn = conectar();
$sql = "SELECT ID_PROVINCIA, NOMBRE_PROVINCIA FROM FIDE_PROVINCIA_TB ORDER BY NOMBRE_PROVINCIA";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

$provincias = [];
while ($row = oci_fetch_assoc($stmt)) {
    $provincias[] = $row;
}

echo json_encode($provincias);
oci_free_statement($stmt);
oci_close($conn);