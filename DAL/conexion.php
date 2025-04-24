<?php

function conectar() {
    $user = "FIDE_ARATIENDA";
    $password = "ARATIENDA";

    
    $os = PHP_OS_FAMILY;

    if ($os === 'Windows') {
        $host = "localhost/XE";
    } else {
        $host = "localhost:1521/ORCLPDB1";  // CAMBIA ESTO de ORCLPDB1 a XE
    }

    $conexion = oci_connect($user, $password, $host);

    if (!$conexion) {
        $m = oci_error();
        echo "<script>console.error('Database connection error: " . addslashes($m['message']) . "');</script>";
        exit;
    }

    return $conexion;
}

function desconectar($conexion) {
    oci_close($conexion);
}