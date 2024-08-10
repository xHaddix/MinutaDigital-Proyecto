<?php
// Conexión a la base de datos (ajusta los datos de conexión según tu configuración)
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

// Verificar si se enviaron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $cedula = $_POST["cedula"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $turno = $_POST["turno"];
    $nombre_empresa = $_POST["nombre_empresa"];

    // Consulta SQL para actualizar el registro en la base de datos
    $sql = "UPDATE Vigilantes 
            SET nombre=?, apellido=?, email=?, telefono=?, turno=?, nombre_empresa=?
            WHERE cedula=?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Vincular los parámetros
    $stmt->bind_param("sssssss", $nombre, $apellido, $email, $telefono, $turno, $nombre_empresa, $cedula);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir después de la actualización
        header("Location: /minutadigital/Admin/Ver_Vigilante/PHP/ver_vigilantes.php");
        exit();
    } else {
        // Error en la actualización
        echo "No se registraron cambios: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>
