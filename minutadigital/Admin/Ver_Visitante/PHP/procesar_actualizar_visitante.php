<?php
// Conexión a la base de datos (ajusta los datos de conexión según tu configuración)
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

// Verificar si se enviaron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $cedula = $_POST["cedula"];
    $nombres = $_POST["nombres"];
    $apellidos = $_POST["apellidos"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $num_apto = $_POST["num_apto"];
    $parqueo = $_POST["parqueo"];
   

    // Consulta SQL para actualizar el registro en la base de datos
    $sql = "UPDATE Visitantes
            SET nombres='$nombres', apellidos='$apellidos', email='$email', telefono='$telefono', 
                num_apto='$num_apto', parqueo='$parqueo' WHERE cedula='$cedula'";

    if ($conn->query($sql) === TRUE) {
        // Redirigir después de la actualización
        header("Location: /minutadigital/Admin/Ver_Visitante/PHP/ver_visitante.php");
        exit();
    } else {
        // Error en la actualización
        echo "Error al actualizar el registro: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>
