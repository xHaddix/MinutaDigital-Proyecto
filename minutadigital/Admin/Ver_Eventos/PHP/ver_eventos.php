<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Eventos</title>
    <link href="/minutadigital/Admin/Ver_Eventos/CSS/evento_crud.css" rel="stylesheet"/>
</head>
<body>
<header>
    <figure>
        <a href="/minutadigital/Admin/Ver_Residente/PHP/ver_residente.php">
            <img class="img_home" src="/minutadigital/Residente/Eventos/IMG/imagenMinuta.jpg" alt="imagen home" />
        </a>
    </figure>
    <div class="titulo">
        <h1 class="titulo_principal">MINUTA DE VIGILANCIA</h1>
        <h2 class="subtitulo">"Portal de la Hacienda 1"</h2>
    </div>
</header>
<center><h1 class="eventos">Lista de Eventos</h1></center>
<table border="1">
    <tr>
        <!--<th>ID</th>-->
        <th>Fecha del Evento</th>
        <th>Hora de Inicio</th>
        <th>Hora de Finalización</th>
        <th>Nombre del Evento</th>
        <th>Cédula del Residente</th>
        <th>Estado</th>
        <th>Salón</th>
        <th>Acciones</th>
    </tr>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "minutadigital";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT eventos.id, eventos.fecha_evento, eventos.hora_evento, eventos.hora_final, eventos.nombre_evento, eventos.cedula_residente, 
            CASE WHEN eventos.estado = 'No Aprobado' THEN 'No Aprobado' ELSE eventos.estado END as estado, salones.nombre_salon, eventos.salon_id
            FROM eventos
            LEFT JOIN salones ON eventos.salon_id = salones.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["fecha_evento"] . "</td>";
            echo "<td>" . $row["hora_evento"] . "</td>";
            echo "<td>" . $row["hora_final"] . "</td>";
            echo "<td>" . $row["nombre_evento"] . "</td>";
            echo "<td>" . $row["cedula_residente"] . "</td>";
            echo "<td>" . $row["estado"] . "</td>";
            echo "<td>" . (!empty($row["nombre_salon"]) ? $row["nombre_salon"] : 'No Asignado') . "</td>";
            echo "<td>
                    <form method='POST' action=''>
                        <input type='hidden' name='evento_id' value='" . $row["id"] . "'>
                        <select name='salon_id'>
                            <option value=''>Seleccionar</option>";
            // Obtener la lista de salones
            $salones_sql = "SELECT id, nombre_salon FROM salones";
            $salones_result = $conn->query($salones_sql);
            while ($salon = $salones_result->fetch_assoc()) {
                $selected = (!empty($row["salon_id"]) && $salon["id"] == $row["salon_id"]) ? 'selected' : '';
                echo "<option value='" . $salon["id"] . "' $selected>" . $salon["nombre_salon"] . "</option>";
            }
            echo "      </select>
                        <br>
                        <button type='submit' name='aprobar' " . ($row["estado"] == 'Aprobado' || $row["estado"] == 'No Aprobado' ? 'disabled' : '') . ">Aprobar</button>
                        <button type='submit' name='rechazar'>Rechazar</button>
                        <button type='submit' name='liberar'>Liberar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No se encontraron eventos</td></tr>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $evento_id = $_POST["evento_id"];
        $salon_id = isset($_POST["salon_id"]) ? $_POST["salon_id"] : null;

        $sql_evento = "SELECT fecha_evento, hora_evento, hora_final FROM eventos WHERE id = ?";
        $stmt_evento = $conn->prepare($sql_evento);
        $stmt_evento->bind_param("i", $evento_id);
        $stmt_evento->execute();
        $result_evento = $stmt_evento->get_result();
        $evento = $result_evento->fetch_assoc();
        $fecha_evento = $evento['fecha_evento'];
        $hora_evento = $evento['hora_evento'];
        $hora_final = $evento['hora_final'];
        $stmt_evento->close();

        if (isset($_POST['aprobar'])) {
            if ($salon_id) {
                $sql_check_salon = "SELECT COUNT(*) as count 
                                    FROM eventos 
                                    WHERE salon_id = ? 
                                    AND fecha_evento = ? 
                                    AND estado = 'Aprobado'
                                    AND ((hora_evento < ? AND hora_final > ?) 
                                    OR (hora_evento < ? AND hora_final > ?) 
                                    OR (hora_evento >= ? AND hora_final <= ?))";
                $stmt_check_salon = $conn->prepare($sql_check_salon);
                $stmt_check_salon->bind_param("ssssssss", $salon_id, $fecha_evento, $hora_evento, $hora_evento, $hora_final, $hora_final, $hora_evento, $hora_final);
                $stmt_check_salon->execute();
                $result_check_salon = $stmt_check_salon->get_result();
                $row_check_salon = $result_check_salon->fetch_assoc();

                if ($row_check_salon['count'] == 0) {
                    $sql_aprobar = "UPDATE eventos SET estado = 'Aprobado', salon_id = ? WHERE id = ?";
                    $stmt_aprobar = $conn->prepare($sql_aprobar);
                    $stmt_aprobar->bind_param("ii", $salon_id, $evento_id);
                    if ($stmt_aprobar->execute()) {
                        echo "<script>alert('Evento aprobado correctamente.'); window.location.href = '/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php';</script>";
                    } else {
                        echo "<script>alert('Error al aprobar el evento.'); window.location.href = '/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php';</script>";
                    }
                    $stmt_aprobar->close();
                } else {
                    echo "<script>alert('El salón seleccionado ya está ocupado. Por favor, seleccione otro salón.');</script>";
                }
                $stmt_check_salon->close();
            } else {
                echo "<script>alert('Por favor, seleccione un salón para aprobar el evento.');</script>";
            }
        } elseif (isset($_POST['rechazar'])) {
            $sql_rechazar = "UPDATE eventos SET estado = 'No Aprobado' WHERE id = ?";
            $stmt_rechazar = $conn->prepare($sql_rechazar);
            $stmt_rechazar->bind_param("i", $evento_id);
            if ($stmt_rechazar->execute()) {
                echo "<script>alert('Evento rechazado correctamente.'); window.location.href = '/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php';</script>";
            } else {
                echo "<script>alert('Error al rechazar el evento.'); window.location.href = '/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php';</script>";
            }
            $stmt_rechazar->close();
        } elseif (isset($_POST['liberar'])) {
            $sql_liberar = "UPDATE eventos SET estado = 'Pendiente', salon_id = NULL WHERE id = ?";
            $stmt_liberar = $conn->prepare($sql_liberar);
            $stmt_liberar->bind_param("i", $evento_id);
            if ($stmt_liberar->execute()) {
                echo "<script>alert('Salón liberado correctamente.'); window.location.href = '/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php';</script>";
            } else {
                echo "<script>alert('Error al liberar el salón.'); window.location.href = '/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php';</script>";
            }
            $stmt_liberar->close();
        }
    }

    $conn->close();
    ?>
</table>
</body>
</html>