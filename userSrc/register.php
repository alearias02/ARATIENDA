<?php
require_once "../DAL/database.php";
require_once "../include/templates/headerUser.php";
require_once "../include/functions/recoge.php";
require_once "../DAL/users.php";

$mensajeErrorU = "";
$mensajeErrorE = "";
$mensajeError = "";

$connection = conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = recogePost('customer_id');
    $user_name = recogePost('user_name');
    $password = recogePost('password');
    $confirm_password = recogePost('Cpassword');
    $nombre_completo = recogePost('name');
    $apellidos_completos = recogePost('lastname');
    $telefono = recogePost('tel');
    $email = recogePost('email');
    $provincia = recogePost('provincia');
    $canton = recogePost('canton');
    $distrito = recogePost('distrito');
    $descripcion = substr(recogePost('descripcion'), 0, 500); // Máximo 500 caracteres
    $pais = 506;

    error_log("POST recibido: provincia=$provincia, canton=$canton, distrito=$distrito, descripcion=$descripcion");

    if ($password !== $confirm_password) {
        $mensajeError = "Las contraseñas no coinciden.";
    }
    if (usuarioExiste($user_name)) {
        $mensajeErrorU = "Este usuario ya existe.";
    }
    if (correoExiste($email)) {
        $mensajeErrorE = "Este correo ya está registrado.";
    }

    if (empty($mensajeError) && empty($mensajeErrorU) && empty($mensajeErrorE)) {
        // Obtener ID_DIRECCION
        $sql_seq = "SELECT FIDE_SEQ_DIRECCION_ID.NEXTVAL AS NEXT_ID FROM DUAL";
        $stmt_seq = oci_parse($connection, $sql_seq);
        oci_execute($stmt_seq);
        $row = oci_fetch_assoc($stmt_seq);
        $id_direccion = $row['NEXT_ID'];
        oci_free_statement($stmt_seq);
        error_log("ID_DIRECCION generado desde secuencia: $id_direccion");

        // Insertar dirección
        $sql_dir = "INSERT INTO FIDE_DIRECCION_TB (
            ID_DIRECCION, ID_PROVINCIA, ID_CANTON, ID_DISTRITO, ID_PAIS, DESCRIPCION
        ) VALUES (
            :id, :prov, :can, :dis, :pais, :descripcion_txt
        )";
        $stmt_dir = oci_parse($connection, $sql_dir);
        oci_bind_by_name($stmt_dir, ':id', $id_direccion);
        oci_bind_by_name($stmt_dir, ':prov', $provincia);
        oci_bind_by_name($stmt_dir, ':can', $canton);
        oci_bind_by_name($stmt_dir, ':dis', $distrito);
        oci_bind_by_name($stmt_dir, ':pais', $pais);
        oci_bind_by_name($stmt_dir, ':descripcion_txt', $descripcion);

        if (!oci_execute($stmt_dir, OCI_NO_AUTO_COMMIT)) {
            $e = oci_error($stmt_dir);
            error_log("Fallo INSERT direccion: " . $e['message']);
            oci_rollback($connection);
        } else {
            error_log("Resultado INSERT direccion: ok");

            // Insertar usuario con nombre y apellidos completos
            $resultado = IngresarUsuarioClienteConDireccion(
                $customer_id,
                $nombre_completo,
                $apellidos_completos,
                $user_name,
                $email,
                $password,
                $telefono,
                $id_direccion
            );

            if ($resultado) {
                error_log("Resultado INSERT usuario: ok");
                oci_commit($connection);
                header("Location: login.php");
                exit();
            } else {
                error_log("Resultado INSERT usuario: falló");
                oci_rollback($connection);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>

<body>
    <main class="main-login">
        <form method="POST" id="form_Register">
            <div class="card-body">
                <p class="p-register required-label">Elementos necesarios:</p>

                <label class="label-login required-label">Cédula de Identidad</label>
                <input type="text" class="input-login" name="customer_id" id="customer_id" required>

                <label class="label-login">Nombre</label>
                <input type="text" class="input-login" name="name" id="name">

                <label class="label-login">Apellidos</label>
                <input type="text" class="input-login" name="lastname" id="lastname">

                <label class="label-login required-label">Usuario</label>
                <input type="text" class="input-login" name="user_name" id="user_name" required>
                <p class="p-register"><?php echo $mensajeErrorU; ?></p>

                <label class="label-login required-label">Correo</label>
                <input type="email" class="input-login" name="email" id="email" required>
                <p class="p-register"><?php echo $mensajeErrorE; ?></p>

                <label class="label-login required-label">Teléfono</label>
                <input type="tel" class="input-login" name="tel" id="tel" required>

                <label class="label-login required-label">Contraseña</label>
                <input type="password" class="input-login" name="password" id="password" required>

                <label class="label-login required-label">Confirmar Contraseña</label>
                <input type="password" class="input-login" name="Cpassword" id="Cpassword" required>
                <p class="p-register" style="color: red;"><?php echo $mensajeError; ?></p>

                <label class="label-login required-label">Provincia</label>
                <select name="provincia" id="provincia" required>
                    <option value="">Seleccione una provincia</option>
                    <?php
                    $query = "SELECT ID_PROVINCIA, NOMBRE_PROVINCIA FROM FIDE_PROVINCIA_TB ORDER BY NOMBRE_PROVINCIA";
                    $stid = oci_parse($connection, $query);
                    oci_execute($stid);
                    while ($row = oci_fetch_assoc($stid)) {
                        echo "<option value='" . $row['ID_PROVINCIA'] . "'>" . $row['NOMBRE_PROVINCIA'] . "</option>";
                    }
                    oci_free_statement($stid);
                    ?>
                </select>

                <label class="label-login required-label">Cantón</label>
                <select name="canton" id="canton" required>
                    <option value="">Seleccione un cantón</option>
                </select>

                <label class="label-login required-label">Distrito</label>
                <select name="distrito" id="distrito" required>
                    <option value="">Seleccione un distrito</option>
                </select>

                <label class="label-login">Descripción de la dirección</label>
                <textarea class="input-login" name="descripcion" id="descripcion"
                    placeholder="Ej: Casa amarilla frente a la soda 'El Tico'..."></textarea>

                <button type="submit" class="button-submit">Registrarse</button>
                <p class="p">¿Posees una cuenta? <a href="login.php">Inicia sesión</a></p>
            </div>
        </form>
    </main>

    <script src="../js/ubicaciones.js"></script>
</body>

</html>