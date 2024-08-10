<?php
// Conexión a la base de datos (ajusta los datos de conexión según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "minutadigital";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se enviaron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $cedula = $_POST["cedula"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $num_apto = $_POST["num_apto"];
    $parqueo = $_POST["parqueo"];

    // Consulta SQL para actualizar el registro en la base de datos
    $sql = "UPDATE Residentes 
            SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono', 
                num_apto='$num_apto', parqueo='$parqueo' 
            WHERE cedula='$cedula'";

    if ($conn->query($sql) === TRUE) {
        // Redirigir después de la actualización
        header("Location: /minutadigital/Vigilante/ver_residente_vigilante/PHP/ver_residente.php");
        exit();
    } else {
        // Error en la actualización
        echo "Error al actualizar el registro: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>
