<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
require_once 'db_connection.php';

// Consulta SQL para obtener todos los registros de correspondencia con información del residente emisor
$sql = "SELECT c.cedula_residente, CONCAT(r.nombre, ' ', r.apellido) AS residente_emisor, c.vigilante_receptor, c.tipo_correspondencia, c.fecha_entrega, c.estado
        FROM correspondencia c
        INNER JOIN residentes r ON c.cedula_residente = r.cedula";

$result = $conn->query($sql);


// Cerrar la conexión, ya que ya obtuvimos los datos necesarios
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/minutadigital/Vigilante/Correspondencia/CSS/Correspondencia_crud.css" />
    <title>Ver Correspondencia de Residentes</title>
    <!-- <script src="./recargar_pagina.js" defer></script> -->
</head>
<body>
    <div class="contenedor">
        <a href="/minutadigital/Vigilante/Vigilante_Home/HTML/Vigilante.html">
            <img class="img_admin_home" src="/minutadigital/Vigilante/Correspondencia/IMG/imagenMinuta.jpg" />
        </a>
        <div class="div_minuta">
            <h1>Minuta de Vigilancia</h1>
            <h2>"Portal de la hacienda 1"</h2>
        </div>
    </div>

    <div>
        <h2 class="listado_usuarios">Listado de Correspondencia de Residentes</h2>
    </div>

    <button onclick='redirigirAFormulario()'>Registrar Correspondencia</button>

    <script>
        function redirigirAFormulario() {
            // Define la URL de la página a la que quieres redirigir
            var urlFormulario = '/minutadigital/Vigilante/Correspondencia/PHP/correspondencia.php';

            // Redirige a la URL del formulario
            window.location.href = urlFormulario;
        }
    </script>

    <table>
        <thead>
            <tr>
                <th>Cedula Residente</th>
                <th>Residente Emisor</th>
                <th>Vigilante Receptor</th>
                <th>Tipo de Correspondencia</th>
                <th>Fecha de Entrega</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <!-- Insertar dinámicamente las filas de la tabla con datos de la base de datos -->
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo "<tr>
                            <td>{$row['cedula_residente']}</td>
                            <td>{$row['residente_emisor']}</td>
                            <td>{$row['vigilante_receptor']}</td>
                            <td>{$row['tipo_correspondencia']}</td>
                            <td>{$row['fecha_entrega']}</td>
                            <td>{$row['estado']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay correspondencias registradas</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
