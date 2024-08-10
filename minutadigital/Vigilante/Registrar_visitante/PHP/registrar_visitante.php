<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

$registro_exitoso = false;
$cedula_error = false;
$email_error = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fechaSalida = $_POST["fechaSalida"];
    $horaSalida = $_POST["horaSalida"];
    $numApto = $_POST["numApto"];
    $parqueo = $_POST["parqueo"];
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $email = $_POST["email"];
    $cedula = $_POST["cedula"];
    $telefono = $_POST["telefono"];

    // Verificar cédula y correo electrónico en Residentes
    $verificar_residentes = $conn->prepare("SELECT * FROM Residentes WHERE cedula = ? OR email = ?");
    $verificar_residentes->bind_param("ss", $cedula, $email);
    $verificar_residentes->execute();
    $resultado_verificacion_residentes = $verificar_residentes->get_result();
    $verificar_residentes->close();

    // Verificar cédula y correo electrónico en Vigilantes
    $verificar_vigilantes = $conn->prepare("SELECT * FROM Vigilantes WHERE cedula = ? OR email = ?");
    $verificar_vigilantes->bind_param("ss", $cedula, $email);
    $verificar_vigilantes->execute();
    $resultado_verificacion_vigilantes = $verificar_vigilantes->get_result();
    $verificar_vigilantes->close();

    // Verificar cédula y correo electrónico en Visitantes
    $verificar_visitantes = $conn->prepare("SELECT * FROM Visitantes WHERE cedula = ? OR email = ?");
    $verificar_visitantes->bind_param("ss", $cedula, $email);
    $verificar_visitantes->execute();
    $resultado_verificacion_visitantes = $verificar_visitantes->get_result();
    $verificar_visitantes->close();

    if (
        $resultado_verificacion_residentes->num_rows > 0 ||
        $resultado_verificacion_vigilantes->num_rows > 0 ||
        $resultado_verificacion_visitantes->num_rows > 0
    ) {
        $cedula_error = true;
        $email_error = true;
        $error_message = "La cédula o el correo electrónico ya están registrados en otro tipo de cuenta. Por favor, use otros datos.";
    } else {
        // Realizar el registro en la tabla de Visitantes
        $sql_visitantes = "INSERT INTO Visitantes (fecha_salida, hora_salida, num_apto, parqueo, nombres, apellidos, email, cedula, telefono) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_visitantes = $conn->prepare($sql_visitantes);
        $stmt_visitantes->bind_param("sssssssss", $fechaSalida, $horaSalida, $numApto, $parqueo, $nombres, $apellidos, $email, $cedula, $telefono);

        if ($stmt_visitantes->execute()) {
            // Aquí puedes realizar el registro en la tabla de Usuarios si lo necesitas
            // ...

            $registro_exitoso = true;
        } else {
            $error_message = "Error al registrar en Visitantes: " . $stmt_visitantes->error;
        }

        $stmt_visitantes->close();
    }
}

$conn->close();

echo "<script>";
if ($cedula_error || $email_error) {
    echo "alert('$error_message');";
    echo "window.location.href = '/minutadigital/Vigilante/Registrar_visitante/HTML/Visitante.html';";
}
if ($registro_exitoso) {
    echo "alert('Registro exitoso');";
    echo "window.location.href = '/minutadigital/Vigilante/Registrar_visitante/HTML/Visitante.html';";
}

echo "</script>";
?>
