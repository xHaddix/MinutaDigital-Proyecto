<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
    require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

    // Obtener la cédula del formulario
    $cedula = $_POST["cedula"];

    // Lógica para eliminar el registro de Visitantes en la base de datos
    $sqlVisitantes = "DELETE FROM Visitantes WHERE cedula = '$cedula'";
    if ($conn->query($sqlVisitantes) === TRUE) {
        // La eliminación de Visitantes fue exitosa

        // Ahora, también eliminamos el registro correspondiente de Usuarios
        $sqlUsuarios = "DELETE FROM Usuarios WHERE cedula_usuario = '$cedula'";
        if ($conn->query($sqlUsuarios) === TRUE) {
            // La eliminación de Usuarios fue exitosa
            echo json_encode(array('message' => "Registros eliminados exitosamente", 'cedula' => $cedula));
        } else {
            // Error en la eliminación de Usuarios
            echo json_encode(array('message' => "Error al eliminar el registro de Usuarios: " . $conn->error));
        }
    } else {
        // Error en la eliminación de Visitantes
        echo json_encode(array('message' => "Error al eliminar el registro de Visitantes: " . $conn->error));
    }

    // Cerrar la conexión
    $conn->close();
}
?>
