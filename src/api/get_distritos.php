<?php
require_once __DIR__ . '/../../DAL/conexion.php';
header("Content-Type: application/json");

$conn = conectar();

if (!$conn) {
    echo json_encode(["error" => "No se pudo conectar"]);
    exit;
}

if (!isset($_GET['canton'])) {
    echo json_encode(["error" => "Falta el parámetro canton"]);
    exit;
}

$id_canton = (int)$_GET['canton']; // Forzar a número entero

// Debug log
error_log("Valor recibido por GET canton: " . $id_canton);

// Consulta
$sql = "SELECT ID_DISTRITO, NOMBRE_DISTRITO FROM FIDE_DISTRITO_TB WHERE ID_CANTON = :id_canton";
error_log("SQL ejecutada: $sql");

$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":id_canton", $id_canton);
oci_execute($stmt);

$result = [];
$count = 0;
while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $result[] = [
        "ID_DISTRITO" => $row['ID_DISTRITO'],
        "NOMBRE_DISTRITO" => $row['NOMBRE_DISTRITO']
    ];
    $count++;
}

error_log("Filas encontradas: $count");

echo json_encode($result);