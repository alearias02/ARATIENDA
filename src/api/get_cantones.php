<?php
require_once __DIR__ . '/../../DAL/conexion.php';

header('Content-Type: application/json');

$provincia_id = isset($_GET['provincia']) ? intval($_GET['provincia']) : 0;

$conn = conectar();

$sql = "SELECT ID_CANTON, NOMBRE_CANTON FROM FIDE_CANTON_TB WHERE ID_PROVINCIA = :provincia ORDER BY NOMBRE_CANTON";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':provincia', $provincia_id);
oci_execute($stid);

$data = [];
while ($row = oci_fetch_assoc($stid)) {
    $data[] = $row;
}

echo json_encode($data);
oci_free_statement($stid);
oci_close($conn);