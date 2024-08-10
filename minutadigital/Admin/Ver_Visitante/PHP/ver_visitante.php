<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
require_once 'db_connection.php';

// Función para eliminar un registro por cédula
function eliminarRegistro($cedula) {
    global $conn;

    // Puedes agregar la lógica para eliminar el registro en la base de datos aquí
    // Por ahora, solo mostraremos un mensaje de alerta
    echo json_encode(array('message' => "Registro eliminado exitosamente", 'cedula' => $cedula));

}

// Consulta SQL para obtener todos los visitantes
$sql = "SELECT * FROM Visitantes";
$result = $conn->query($sql);

// Cerrar la conexión, ya que ya obtuvimos los datos necesarios
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/minutadigital/Admin/Ver_Visitante/CSS/admin_visitantes.css" />
    <title>Ver Visitantes</title>
    <script src="/minutadigital/Admin/Ver_Visitante/JS/recargar_pagina.js" defer>
    </script>
    <script>
        // Función para actualizar un registro por cédula (sin implementar la lógica específica)
        function actualizarRegistro(cedula) {
            // Redirigir a la página de actualización con la cédula como parámetro
            window.location.href = "actualizar_visitante.php?cedula=" + cedula;
        }
    </script>
</head>
<body>
    <div class="contenedor">
        <a href="/minutadigital/Admin/admin_home/HTML/admin_home.html">
            <img class="img_admin_home" src="/minutadigital/Admin/Ver_Visitante/IMG/imagenMinuta.jpg" />
        </a>
        <div class="div_minuta">
            <h1>Minuta de Vigilancia</h1>
            <h2>"Portal de la hacienda 1"</h2>
        </div>
    </div>

    <div>
        <h2 class="listado_usuarios">Listado de Visitantes:</h2>
        <h2 class="admin">Administrador</h2>
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
                <th>Hora Salida</th>
                <th>Fecha Salida</th>
                <th>Acciones</th> <!-- Nueva columna -->
            </tr>
        </thead>
        <tbody>
            <!-- Insertar dinámicamente las filas de la tabla con datos de la base de datos -->
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['cedula']}</td>
                            <td>{$row['nombres']}</td>
                            <td>{$row['apellidos']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['telefono']}</td>
                            <td>{$row['num_apto']}</td>
                            <td>{$row['parqueo']}</td>
                            <td>{$row['hora_salida']}</td>
                            <td>{$row['fecha_salida']}</td>
                            <td>
                                <button onclick=\"eliminarRegistro('{$row['cedula']}')\">Eliminar</button>
                                <button onclick=\"actualizarRegistro('{$row['cedula']}')\">Actualizar</button>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay visitantes registrados</td></tr>";
            }
            ?>
            
        </tbody>
    </table>
</body>
</html>
