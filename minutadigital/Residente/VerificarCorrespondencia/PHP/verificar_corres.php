<?php
require_once 'db_connection.php';

// Asegúrate de iniciar la sesión antes de acceder a $_SESSION
session_start();

// Supongamos que la cédula del residente se almacena en la sesión bajo la clave 'cedula_usuario'
$cedula_residente = $_SESSION['cedula_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_correspondencia'])) {
    $id_correspondencia = $_POST['id_correspondencia'];

    // Obtener la fecha actual
    $fecha_actual = date('Y-m-d');

    // Actualizar la fecha de entrega y la fecha de llegada al marcar como retirado
    $sql_update = "UPDATE correspondencia 
                   SET estado = 'retirado', fecha_entrega = '$fecha_actual'
                   WHERE id_correspondencia = $id_correspondencia AND cedula_residente = '$cedula_residente' AND estado = 'pendiente'";

    $result_update = $conn->query($sql_update);

    if ($result_update) {
        $response = array('status' => 'success', 'message' => 'Correspondencia retirada correctamente');
        echo json_encode($response);
        exit;
    } else {
        $response = array('status' => 'error', 'message' => 'Error al retirar la correspondencia');
        echo json_encode($response);
        exit;
    }
}

// Consulta de correspondencia pendiente
$sql_correspondencia = "SELECT id_correspondencia, tipo_correspondencia, fecha_entrega, estado
                        FROM correspondencia
                        WHERE cedula_residente = '$cedula_residente' AND (estado = 'pendiente' OR estado = 'Retirado')";
$result_correspondencia = $conn->query($sql_correspondencia);

// Obtener el nombre del residente
$sql_residente = "SELECT nombre FROM Residentes WHERE cedula = '$cedula_residente'";
$result_residente = $conn->query($sql_residente);
$nombre_residente = "";

if ($result_residente->num_rows > 0) {
    $row_residente = $result_residente->fetch_assoc();
    $nombre_residente = $row_residente['nombre'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/minutadigital/Residente/VerificarCorrespondencia/CSS/VerificarCorrespondencia.css">
    <title>Verificar Correspondencia</title>
</head>

<body>
    <header>
        <a href='/minutadigital/Residente/Residente_home/PHP/Residente_home.php'>
            <img class="header" src="/minutadigital/Residente/VerificarCorrespondencia/IMG/imagenMinuta.jpg" alt="imagen home" />
        </a>
    </header>

    <div class="contenedor">
        <h1>Bienvenido,
            <?php echo $nombre_residente; ?>
        </h1>
        <h2>
            <center>Correspondencia Pendiente</center>
        </h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Correspondencia</th>
                    <th>Fecha de Entrega</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_correspondencia->num_rows > 0) {
                    while ($row = $result_correspondencia->fetch_assoc()) {
                        echo "<tr>
                                <td><center>{$row['tipo_correspondencia']}</center></td>
                                <td><center>{$row['fecha_entrega']}</center></td>
                                <td>{$row['estado']}</td>
                                <td>
                                <button class='retirado' onclick='marcarComoRetirado({$row['id_correspondencia']})'>Confirmar Retiro</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'><center>No hay correspondencias</center></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function marcarComoRetirado(id_correspondencia) {
            var confirmar = confirm("¿Estás seguro de que deseas marcar como retirada esta correspondencia?");
            if (confirmar) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        alert(response.message);
                        location.reload();
                    }
                };

                xhr.open("POST", "verificar_corres.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("id_correspondencia=" + id_correspondencia);
            } else {
                alert("Acción cancelada");
            }
        }
    </script>
</body>

</html>