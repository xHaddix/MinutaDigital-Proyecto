<?php
// Iniciar la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Eventos</title>
    <link href="/minutadigital/Residente/Eventos/CSS/eventos.css" rel="stylesheet" />
</head>
<body>
    <header>
        <figure>
            <a href="/minutadigital/Residente/Eventos/PHP/eventos_crud.php">
                <img class="img_home" src="/minutadigital/Residente/EventoS/IMG/imagenMinuta.jpg" alt="imagen home" />
            </a>
        </figure>
        <div class="titulo">
            <h1 class="titulo_principal">MINUTA DE VIGILANCIA</h1>
            <h2 class="subtitulo">"Portal de la Hacienda 1"</h2>
        </div>
        <div class="titulo">
            <h1 class="titulo_principal">Eventos</h1>
        </div>
    </header>
    <div class="contenedor">
        <form id="formularioEventos" action="evento.php" method="POST">
            <div class="fila-formulario">
                <label for="fechaEvento">Fecha del Evento:</label>
                <input type="date" id="fechaEvento" name="fecha_evento" required />
            </div>

            <div class="fila-formulario">
                <label for="horaEvento">Hora del Evento:</label>
                <input type="time" id="horaEvento" name="hora_evento" required />
            </div>

            <div class="fila-formulario">
                <label for="horaFinalEvento">Hora de Finalización:</label>
                <input type="time" id="horaFinalEvento" name="hora_final_evento" required />
            </div>

            <div class="fila-formulario">
                <label for="nombreEvento">Nombre del Evento:</label>
                <input type="text" id="nombreEvento" name="nombre_evento" required />
            </div>

            <?php
            // Verificar si la sesión está iniciada y la cédula del residente está definida en la sesión
            if (isset($_SESSION['cedula_usuario'])) {
                echo '<input type="hidden" id="cedula_residente" name="cedula_residente" value="' . $_SESSION['cedula_usuario'] . '" />';
            } else {
                // Si la cédula del residente no está definida en la sesión, mostrar un mensaje de error
                echo '<p>Error: La sesión no se ha iniciado correctamente. Por favor, inicie sesión y vuelva a intentarlo.</p>';
            }
            ?>

            <div class="boton-formulario">
                <button type="submit" class="btn primary">Registrar</button>
            </div>
        </form>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "minutadigital";
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
    
        $cedulaResidente = $_POST["cedula_residente"];
        $fechaEvento = $_POST["fecha_evento"];
        $horaEvento = $_POST["hora_evento"];
        $horaFinalEvento = $_POST["hora_final_evento"];
        $nombreEvento = $_POST["nombre_evento"];
        $estado = "Pendiente";
    
        $sqlInsertEvento = "INSERT INTO eventos (fecha_evento, hora_evento, hora_final, nombre_evento, cedula_residente, estado) 
                            VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertEvento = $conn->prepare($sqlInsertEvento);
        $stmtInsertEvento->bind_param("ssssss", $fechaEvento, $horaEvento, $horaFinalEvento, $nombreEvento, $cedulaResidente, $estado);
    
        if ($stmtInsertEvento->execute()) {
            echo "<script>alert('Evento registrado con éxito'); window.location.href = '/minutadigital/Residente/Eventos/PHP/eventos_crud.php';</script>";
        } else {
            echo "<script>alert('Error al registrar evento: " . $stmtInsertEvento->error . "');</script>";
        }
    
        $stmtInsertEvento->close();
        $conn->close();
    }
    ?>
</body>
</html>
