<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
    require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

    // Obtener la cédula del formulario
    $cedula = $_POST["cedula"];

    // Iniciar una transacción para asegurar la consistencia de la base de datos
    $conn->begin_transaction();

    try {
        // Lógica para eliminar el registro de Vigilantes en la base de datos
        $sqlVigilantes = "DELETE FROM Vigilantes WHERE cedula = '$cedula'";
        $conn->query($sqlVigilantes);

        // También eliminamos el registro correspondiente de Usuarios
        $sqlUsuarios = "DELETE FROM Usuarios WHERE cedula_usuario = '$cedula'";
        $conn->query($sqlUsuarios);

        // Confirmar la transacción
        $conn->commit();

        // La eliminación fue exitosa
        echo json_encode(array('message' => "Registros eliminados exitosamente", 'cedula' => $cedula));
    } catch (Exception $e) {
        // Ocurrió un error, revertir la transacción y devolver un mensaje de error
        $conn->rollback();
        echo json_encode(array('message' => "Error al eliminar el registro: " . $e->getMessage()));
    }

    // Cerrar la conexión
    $conn->close();
}
?>
