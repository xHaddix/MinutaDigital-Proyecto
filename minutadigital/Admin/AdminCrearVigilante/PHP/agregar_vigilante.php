<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

$registro_exitoso = false;
$cedula_error = false;
$nombre_error = false;
$apellido_error = false;
$telefono_error = false;
$email_error = false;
$turno_error = false;
$contrasena_error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $turno = $_POST['turno'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $contrasena = $_POST['contrasena'];

    // Verificar si el usuario ya está registrado en la base de datos
    $verificar_usuario = $conn->prepare("SELECT * FROM Vigilantes WHERE cedula = ?");
    $verificar_usuario->bind_param("s", $cedula);
    $verificar_usuario->execute();
    $resultado_verificacion = $verificar_usuario->get_result();
    $verificar_usuario->close();

    if ($resultado_verificacion->num_rows > 0) {
        $cedula_error = true;
        $error_message = "El usuario con esta cédula ya está registrado. Por favor, utilice otra cédula.";
    } else {
        // Validar que la cédula tenga exactamente 10 dígitos y sean números
        if (!preg_match("/^[0-9]{10}$/", $cedula)) {
            $cedula_error = true;
            $error_message .= "La cédula debe tener exactamente 10 dígitos y ser numérica. ";
        }

        // Validar que el nombre tenga entre 8 y 50 caracteres y no contenga números ni símbolos especiales
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]{8,50}$/", $nombre)) {
            $nombre_error = true;
            $error_message .= "El nombre debe tener entre 8 y 50 caracteres y no debe contener números ni símbolos especiales. ";
        }

        // Validar que el apellido tenga entre 8 y 50 caracteres y no contenga números ni símbolos especiales
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]{8,50}$/", $apellido)) {
            $apellido_error = true;
            $error_message .= "El apellido debe tener entre 8 y 50 caracteres y no debe contener números ni símbolos especiales. ";
        }

        // Validar que el teléfono tenga entre 7 y 15 caracteres y sean números
        if (!preg_match("/^[0-9]{7,15}$/", $telefono)) {
            $telefono_error = true;
            $error_message .= "El teléfono debe tener entre 7 y 15 dígitos y ser numérico. ";
        }

        // Validar el formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = true;
            $error_message .= "El formato del correo electrónico es inválido. ";
        }

        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]{8,50}$/", $nombre_empresa)) {
            $nombre_error = true;
            $error_message .= "El nombre de empresa debe tener entre 8 y 50 caracteres y no debe contener números ni símbolos especiales. ";
        }

        // Validar que la contraseña tenga al menos 8 caracteres
        if (strlen($contrasena) < 8) {
            $contrasena_error = true;
            $error_message .= "La contraseña debe tener al menos 8 caracteres. ";
        }

        // Si no hay errores en las validaciones, proceder con el registro
        if (!$cedula_error && !$nombre_error && !$apellido_error && !$telefono_error && !$email_error && !$turno_error && !$contrasena_error) {
            // Realizar el registro en la tabla de Vigilantes
            $sql_vigilantes = "INSERT INTO Vigilantes (cedula, nombre, apellido, email, telefono, turno, nombre_empresa) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_vigilantes = $conn->prepare($sql_vigilantes);
            $stmt_vigilantes->bind_param("sssssss", $cedula, $nombre, $apellido, $email, $telefono, $turno, $nombre_empresa);

            if ($stmt_vigilantes->execute()) {
                // Obtener el ID del último registro insertado
                $vigilante_id = $stmt_vigilantes->insert_id;

                // Realizar el registro en la tabla de Usuarios con el rol "vigilante"
                $sql_usuarios = "INSERT INTO Usuarios (cedula_usuario, contrasena, rol) VALUES (?, ?, 'vigilante')";
                $stmt_usuarios = $conn->prepare($sql_usuarios);
                $stmt_usuarios->bind_param("ss", $cedula, $contrasena);

                if ($stmt_usuarios->execute()) {
                    $registro_exitoso = true;
                } else {
                    $error_message = "Error al registrar en Usuarios: " . $stmt_usuarios->error;
                }

                $stmt_usuarios->close();
            } else {
                $error_message = "Error al registrar en Vigilantes: " . $stmt_vigilantes->error;
            }

            $stmt_vigilantes->close();
        }
    }
}

$conn->close();

echo "<script>";
if ($cedula_error || $nombre_error || $apellido_error || $telefono_error || $email_error || $turno_error || $contrasena_error) {
    echo "alert('$error_message');";
    echo "window.location.href = '/minutadigital/Admin/AdminCrearVigilante/HTML/Admin_crear_vigilantes.html';";
}
if ($registro_exitoso) {
    echo "alert('Registro exitoso');";
    echo "window.location.href = '/minutadigital/Admin/Ver_Vigilante/PHP/ver_vigilantes.php';";
}
echo "</script>";
?>
