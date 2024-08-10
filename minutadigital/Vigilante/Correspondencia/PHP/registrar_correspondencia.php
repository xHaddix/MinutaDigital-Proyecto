<?php
// Conexión a la base de datos (ajusta los datos de conexión según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minutadigital";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$personaRecibe = $_POST["personaRecibe"];
$personaEntrega = $_POST["personaEntrega"];
$tipoCorrespondencia = $_POST["tipoCorrespondencia"];

// Insertar datos en la tabla Correspondencia
$sql = "INSERT INTO Correspondencia (persona_recibe, persona_entrega, tipo_correspondencia) 
        VALUES ('$personaRecibe', '$personaEntrega', '$tipoCorrespondencia')";

if ($conn->query($sql) === TRUE) {
    echo "Correspondencia registrada con éxito";
} else {
    echo "Error al registrar correspondencia: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
