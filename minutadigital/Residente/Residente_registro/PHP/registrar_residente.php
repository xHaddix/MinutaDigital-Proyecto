<?php
// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minutadigital";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicialización de variables y banderas
$error_message = "";

// Si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $num_apartamento = $_POST['num_apartamento'];
    $parqueo = $_POST['parqueo'];
    $contrasena = $_POST['contrasena'];

    // Validación de datos
    if (!preg_match('/^\d{10}$/', $cedula)) {
        $error_message .= "La cédula debe tener 10 dígitos numéricos.\\n";
    }

    if (strlen($nombre) < 8) {
        $error_message .= "El nombre debe tener al menos 8 caracteres.\\n";
    }
    
    if (strlen($apellido) < 8) {
        $error_message .= "El apellido debe tener al menos 8 caracteres.\\n";
    }
    
    if (!preg_match('/^\d{1,15}$/', $telefono)) {
        $error_message .= "El teléfono debe contener solo números y tener máximo 15 dígitos.\\n";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message .= "El correo electrónico no es válido.\\n";
    }

    if (!preg_match('/^\d{1,10}$/', $num_apartamento)) {
        $error_message .= "El número de apartamento debe contener solo números y tener máximo 10 dígitos.\\n";
    }

    if (!preg_match('/^\d{1,10}$/', $parqueo)) {
        $error_message .= "El número de parqueo debe contener solo números y tener máximo 10 dígitos.\\n";
    }

    if (strlen($contrasena) < 8) {
        $error_message .= "La contraseña debe tener al menos 8 caracteres.\\n";
    }

    // Verificar si la cédula o el correo ya están registrados
    $verificar_cedula_correo = $conn->prepare("SELECT * FROM Residentes WHERE cedula = ? OR email = ?");
    $verificar_cedula_correo->bind_param("ss", $cedula, $email);
    $verificar_cedula_correo->execute();
    $resultado_verificacion = $verificar_cedula_correo->get_result();
    $verificar_cedula_correo->close();

    if ($resultado_verificacion->num_rows > 0) {
        $error_message .= "La cédula o el correo electrónico ya están registrados. Por favor, use otros datos.\\n";
    }

    // Si no hay errores de validación ni datos duplicados, proceder con el registro
    if (empty($error_message)) {
        // Registro en la tabla de Residentes
        $sql_residentes = "INSERT INTO Residentes (cedula, nombre, apellido, email, telefono, num_apto, parqueo) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_residentes = $conn->prepare($sql_residentes);
        $stmt_residentes->bind_param("sssssss", $cedula, $nombre, $apellido, $email, $telefono, $num_apartamento, $parqueo);

        if ($stmt_residentes->execute()) {
            // Registro exitoso en Residentes

            // Registro en la tabla de Usuarios solo si la inserción en Residentes fue exitosa
            $sql_usuarios = "INSERT INTO Usuarios (cedula_usuario, contrasena, rol) 
                             VALUES (?, ?, 'residente')";
            $stmt_usuarios = $conn->prepare($sql_usuarios);
            $stmt_usuarios->bind_param("ss", $cedula, $contrasena);

            if ($stmt_usuarios->execute()) {
                // Registro exitoso en Usuarios
                echo "<script>alert('Registro exitoso'); window.location.href = '/minutadigital/Login/PHP/login.php';</script>";
                exit; // Terminar el script después de redirigir
            } else {
                // Error al registrar en Usuarios
                $error_message .= "Error al registrar en Usuarios: " . $stmt_usuarios->error . ". ";
            }

            $stmt_usuarios->close();
        } else {
            // Error al registrar en Residentes
            $error_message .= "Error al registrar en Residentes: " . $stmt_residentes->error . ". ";
        }

        $stmt_residentes->close();
    }
}

// Cerrar conexión a la base de datos
$conn->close();

// Si llegamos aquí, significa que hubo un error
echo "<script>alert('$error_message'); window.location.href = '/minutadigital/Residente/Residente_registro/HTML/residente.html';</script>";
?>
