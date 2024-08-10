<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Eventos</title>
    <link href="/minutadigital/Residente/Eventos/CSS/evento_crud.css" rel="stylesheet"/>
</head>
<body>
<header>
    <figure>
        <a href="/minutadigital/Residente/Residente_home/PHP/Residente_home.php">
            <img class="img_home" src="/minutadigital/Residente/Eventos/IMG/imagenMinuta.jpg" alt="imagen home" />
        </a>
    </figure>
    <div class="titulo">
        <h1 class="titulo_principal">MINUTA DE VIGILANCIA</h1>
        <h2 class="subtitulo">"Portal de la Hacienda 1"</h2>
    </div>
</header>
<center><h1 class="eventos">Lista de Eventos</h1></center>
<form action="/minutadigital/Residente/Eventos/PHP/evento.php">
    <button>Registrar Evento</button>
</form>
<table border="1">
    <tr>
        <!--<th>ID</th>-->
        <th>Fecha del Evento</th>
        <th>Hora de Inicio</th>
        <th>Hora de Finalización</th>
        <th>Nombre del Evento</th>
        <!-- <th>Cédula del Residente</th> -->
        <th>Estado</th>
        <th>Salón Asignado</th>
    </tr>
    <?php
    session_start();
// Verificar si la cédula está definida en la sesión
if (!isset($_SESSION['cedula_usuario'])) {
    // Si la cédula no está definida en la sesión, redirigir al usuario al formulario de inicio de sesión
    header("Location: login.php");
    exit();
}

    $cedula_residente = $_SESSION['cedula_usuario'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "minutadigital";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT eventos.id, eventos.fecha_evento, eventos.hora_evento, eventos.hora_final, eventos.nombre_evento, eventos.cedula_residente, eventos.estado, salones.nombre_salon
            FROM eventos
            LEFT JOIN salones ON eventos.salon_id = salones.id
            WHERE eventos.cedula_residente = '$cedula_residente'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["fecha_evento"] . "</td>";
            echo "<td>" . $row["hora_evento"] . "</td>";
            echo "<td>" . $row["hora_final"] . "</td>";
            echo "<td>" . $row["nombre_evento"] . "</td>";
            // echo "<td>" . $row["cedula_residente"] . "</td>";
            echo "<td>" . $row["estado"] . "</td>";
            echo "<td>" . ($row["nombre_salon"] ? $row["nombre_salon"] : 'No Asignado') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No se encontraron eventos</td></tr>";
    }

    $conn->close();
    ?>
</table>
</body>
</html>
