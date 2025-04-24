<?php
require_once "conexion.php";

function usuarioExiste($user_name) {
    $conn = conectar();
    $sql = "SELECT COUNT(*) AS TOTAL FROM FIDE_ARATIENDA.FIDE_USUARIOS_TB WHERE USER_NAME = :user_name";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":user_name", $user_name);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    return ($row && $row['TOTAL'] > 0);
}

function correoExiste($email) {
    $conn = conectar();
    $sql = "SELECT COUNT(*) AS TOTAL FROM FIDE_ARATIENDA.FIDE_USUARIOS_TB WHERE CORREO = :email";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":email", $email);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
    oci_close($conn);
    return ($row && $row['TOTAL'] > 0);
}

function IngresarUsuarioClienteConDireccion($customer_id, $nombre, $apellidos, $user_name, $user_email, $password, $telefono, $id_direccion) {
    $conn = conectar();
    $retorno = false;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql_id = "SELECT NVL(MAX(ID_USUARIO), 0) + 1 AS NEXT_ID FROM FIDE_ARATIENDA.FIDE_USUARIOS_TB";
    $stmt_id = oci_parse($conn, $sql_id);
    oci_execute($stmt_id);
    $row_id = oci_fetch_assoc($stmt_id);
    $id_usuario = $row_id['NEXT_ID'];
    oci_free_statement($stmt_id);

    $sql = "INSERT INTO FIDE_ARATIENDA.FIDE_USUARIOS_TB (
                ID_USUARIO, NOMBRE, APELLIDO1, USER_NAME, CORREO, TELEFONO,
                CONTRASENA, ID_DIRECCION, ID_ROL, ESTADO, CREADO_POR, CREADO_EL
            ) VALUES (
                :id_usuario, :nombre, :apellidos, :user_name, :correo, :telefono,
                :contrasena, :id_direccion, 3, 1, 'SELF-USER', CURRENT_TIMESTAMP
            )";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_usuario", $id_usuario);
    oci_bind_by_name($stmt, ":nombre", $nombre);
    oci_bind_by_name($stmt, ":apellidos", $apellidos);
    oci_bind_by_name($stmt, ":user_name", $user_name);
    oci_bind_by_name($stmt, ":correo", $user_email);
    oci_bind_by_name($stmt, ":telefono", $telefono);
    oci_bind_by_name($stmt, ":contrasena", $hashedPassword);
    oci_bind_by_name($stmt, ":id_direccion", $id_direccion);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $retorno = true;
    } else {
        oci_rollback($conn);
    }

    oci_free_statement($stmt);
    oci_close($conn);
    return $retorno;
}