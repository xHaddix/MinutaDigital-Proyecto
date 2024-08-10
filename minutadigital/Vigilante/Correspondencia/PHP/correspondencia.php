<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Recibir los datos del formulario
    $cedulaResidente = $_POST["cedulaResidente"];  // Asegúrate de que el nombre del campo coincida con tu formulario
    $tipoCorrespondencia = $_POST["tipoCorrespondencia"];  // Asegúrate de que el nombre del campo coincida con tu formulario

    // Verificar si la cédula coincide con algún residente de la tabla Usuarios
    $sqlVerificarResidente = "SELECT cedula_usuario FROM Usuarios WHERE cedula_usuario = ?";
    $stmtVerificarResidente = $conn->prepare($sqlVerificarResidente);
    $stmtVerificarResidente->bind_param("s", $cedulaResidente);
    $stmtVerificarResidente->execute();
    $stmtVerificarResidente->store_result();

    if ($stmtVerificarResidente->num_rows > 0) {
        // Cerrar la conexión después de verificar la cédula
        $stmtVerificarResidente->close();

        // Insertar datos en la tabla Correspondencia
        $vigilanteReceptor = $_POST["nombreVigilante"];  // Asegúrate de que el nombre del campo coincida con tu formulario
        $sqlInsertCorrespondencia = "INSERT INTO Correspondencia (vigilante_receptor, residente_emisor, tipo_correspondencia, cedula_residente, estado) 
        VALUES (?, '', ?, ?, 'pendiente')";
        $stmtInsertCorrespondencia = $conn->prepare($sqlInsertCorrespondencia);
        $stmtInsertCorrespondencia->bind_param("sss", $vigilanteReceptor, $tipoCorrespondencia, $cedulaResidente);

        if ($stmtInsertCorrespondencia->execute()) {
            // Cerrar la conexión después de insertar los datos
            $stmtInsertCorrespondencia->close();
            $conn->close();
            echo json_encode(array("status" => "success", "message" => "Correspondencia registrada con éxito"));
            exit();
        } else {
            echo json_encode(array("status" => "error", "message" => "Error al registrar correspondencia: " . $stmtInsertCorrespondencia->error));
            exit();
        }
    } else {
        // Cerrar la conexión si la cédula no coincide con ningún residente
        $stmtVerificarResidente->close();
        $conn->close();
        echo json_encode(array("status" => "error", "message" => "Residente no encontrado"));
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Correspondencia</title>
    <!-- Agrega aquí tus enlaces a CSS y otros recursos necesarios -->
    <link rel="stylesheet" href="/minutadigital/Vigilante/Correspondencia/CSS/Correspondencia.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> <!-- Agrega jQuery -->
</head>
<body>
<main>
    <header>
        <figure>
            <a href="/minutadigital/Vigilante/Correspondencia/PHP/Correspondencia_crud.php">
                <img class="img_home" src="/minutadigital/Vigilante/Correspondencia/IMG/imagenMinuta.jpg" alt="imagen home" />
            </a>
        </figure>
        <div class="titulo">
            <h1 class="titulo_principal">MINUTA DE VIGILANCIA</h1>
            <h2 class="subtitulo">"Portal de la Hacienda 1"</h2>
        </div>
        <div class="titulo">
            <h1 class="titulo_principal">Correspondencia</h1>
        </div>
    </header>
    <div class="contenedor">
        <form id="formularioCorrespondencia" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="fila-formulario">
                <label for="nombreVigilante">Nombre del Vigilante:</label>
                <input type="text" id="nombreVigilante" name="nombreVigilante" required />
            </div>

            <div class="fila-formulario">
                <label for="cedulaResidente">Cédula del Residente:</label>
                <input type="text" id="cedulaResidente" name="cedulaResidente" required />
            </div>

            <div class="fila-formulario">
                <label for="tipoCorrespondencia">Tipo de Correspondencia:</label>
                <input type="text" id="tipoCorrespondencia" name="tipoCorrespondencia" required />
            </div>

            <!-- Otros campos del formulario -->

            <div class="boton-formulario">
                <button type="button" class="btn primary" onclick="registrarCorrespondencia()">Registrar</button>
            </div>
        </form>
    </div>
</main>

<script>
    function registrarCorrespondencia() {
        // Realizar la petición AJAX para enviar el formulario de manera asíncrona
        $.ajax({
            type: "POST",
            url: "correspondencia.php",
            data: $("#formularioCorrespondencia").serialize(), // Serializar el formulario
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    // Mostrar mensaje de éxito
                    alert(response.message);
                    // Redirigir al CRUD después del registro exitoso
                    window.location.href = "Correspondencia_crud.php";
                } else {
                    // Mostrar mensaje de error
                    alert(response.message);
                    // Puedes agregar más lógica aquí según tus necesidades
                }
            },
            error: function(xhr, status, error) {
                // Mostrar mensaje de error genérico
                alert("Error al procesar la solicitud. Por favor, inténtelo de nuevo más tarde.");
            }
        });
    }
</script>
</body>
</html>
