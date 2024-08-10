<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
require_once 'db_connection.php';

// Función para eliminar un registro por cédula
function eliminarRegistro($cedula) {
    global $conn;

    // Eliminar residente
    $sql_delete_residente = "DELETE FROM Residentes WHERE cedula = '$cedula'";
    $result_delete_residente = $conn->query($sql_delete_residente);

    // Eliminar eventos asociados al residente
    $sql_delete_eventos = "DELETE FROM Eventos WHERE cedula_residente = '$cedula'";
    $result_delete_eventos = $conn->query($sql_delete_eventos);

    if ($result_delete_residente && $result_delete_eventos) {
        echo json_encode(array('message' => "Registro eliminado exitosamente", 'cedula' => $cedula));
    } else {
        echo json_encode(array('message' => "Error al eliminar el registro", 'cedula' => $cedula));
    }
}

// Consulta SQL para obtener todos los residentes
$sql = "SELECT * FROM Residentes";
$result = $conn->query($sql);

// Cerrar la conexión, ya que ya obtuvimos los datos necesarios
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/minutadigital/Admin/Ver_Residente/CSS/admin_residentes.css" />
    <title>Ver Visitantes</title>
    <script src="/minutadigital/Admin/Ver_Residente/JS/recargar_pagina.js" defer></script>
    <script>
        // Función para actualizar un registro por cédula (sin implementar la lógica específica)
        function actualizarRegistro(cedula) {
            // Redirigir a la página de actualización con la cédula como parámetro
            window.location.href = "actualizar_residente.php?cedula=" + cedula;
        }
    </script>
</head>
<body>
    <div class="contenedor">
        <a href="/minutadigital/Admin/admin_home/HTML/admin_home.html">
            <img src="/minutadigital/Admin/Ver_Residente/IMG/imagenMinuta.jpg" />
        </a>
        <div class="div_minuta">
            <h1>Minuta de Vigilancia</h1>
            <h2>"Portal de la hacienda 1"</h2>
        </div>
    </div>

    <div>
        <h2 class="listado_usuarios">Listado de Residentes:</h2>
        <h2 class="admin">Administrador</h2>
        <a href="/minutadigital/Admin/Ver_Eventos/PHP/ver_eventos.php">
            <button class="eventosBTN">Verificar Eventos</button>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Número Apto</th>
                <th>Parqueo</th>
                <th>Acciones</th> <!-- Nueva columna -->
            </tr>
        </thead>
        <tbody>
            <!-- Insertar dinámicamente las filas de la tabla con datos de la base de datos -->
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['cedula']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['apellido']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['telefono']}</td>
                            <td>{$row['num_apto']}</td>
                            <td>{$row['parqueo']}</td>
                            <td>
                                <button onclick=\"eliminarRegistro('{$row['cedula']}')\">Eliminar</button>
                                <button onclick=\"actualizarRegistro('{$row['cedula']}')\">Actualizar</button>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay residentes registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
