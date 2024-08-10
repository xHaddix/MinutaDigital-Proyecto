<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minutadigital";

// Obtener la cédula del formulario
$cedula_usuario = $_POST['cedula'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Iniciar una transacción para asegurar la consistencia de la base de datos
$conn->begin_transaction();

try {
    // Consulta SQL para eliminar los eventos asociados al residente
    $sqlEliminarEventos = "DELETE FROM eventos WHERE cedula_residente = '$cedula_usuario'";
    $conn->query($sqlEliminarEventos);
    
    // Consulta SQL para eliminar el registro en la tabla Residentes
    $sqlEliminarResidente = "DELETE FROM Residentes WHERE cedula = '$cedula_usuario'";
    
    // Consulta SQL para eliminar el registro en la tabla Usuarios
    $sqlEliminarUsuario = "DELETE FROM Usuarios WHERE cedula_usuario = '$cedula_usuario'";

    // Ejecutar las consultas SQL
    $conn->query($sqlEliminarResidente);
    $conn->query($sqlEliminarUsuario);

    // Confirmar la transacción
    $conn->commit();

    // La eliminación fue exitosa
    $response = array('message' => 'Registro eliminado exitosamente');
    
} catch (Exception $e) {
    // Ocurrió un error, revertir la transacción y devolver un mensaje de error
    $conn->rollback();
    $response = array('message' => 'Error al eliminar el registro: ' . $e->getMessage());
}

// Cerrar la conexión
$conn->close();

// Devolver la respuesta como JSON
echo json_encode($response);
?>
