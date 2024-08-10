<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

// Obtener la cédula del vigilante a actualizar desde la URL
$cedula = $_GET['cedula'];

// Consulta SQL para obtener los datos del vigilante
$sql = "SELECT * FROM Visitantes WHERE cedula = '$cedula'";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados de la consulta
if ($result !== false && $result->num_rows > 0) {
    // Obtener los datos del visitante
    $visitante = $result->fetch_assoc();

    // Mostrar el formulario de actualización
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="/minutadigital/Admin/Ver_Visitante/CSS/actualizar_visitante.css" />
        <title>Actualizar Visitante</title>
    </head>
    <body>
        <div class="contenedor">
            <a href="/minutadigital/Admin/Ver_Visitante/PHP/ver_visitante.php"><img class="img_admin_home" src="/minutadigital/Admin/Ver_Visitante/IMG/imagenMinuta.jpg" /></a>
            
            <div class="div_minuta">
                <h1>Minuta de Vigilancia</h1>
                <h2>"Portal de la hacienda 1"</h2>
            </div>
        </div>

        <div>
            <h2 class="listado_usuarios">Actualizar Visitante:</h2>
            <h2 class="admin">Administrador</h2>
        </div>

        <form action="procesar_actualizar_visitante.php" method="post">
            <input type="hidden" name="cedula" value="<?php echo $visitante['cedula']; ?>" />
            <label for="nombres">Nombre:</label>
            <input type="text" name="nombres" value="<?php echo $visitante['nombres']; ?>" required />
            
            <label for="apellidos">Apellido:</label>
            <input type="text" name="apellidos" value="<?php echo $visitante['apellidos']; ?>" required />
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $visitante['email']; ?>" required />
            
            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" value="<?php echo $visitante['telefono']; ?>" required />
            
            <label for="num_apto">Número Apto:</label>
            <input type="text" name="num_apto" value="<?php echo $visitante['num_apto']; ?>" required />
            
            <label for="parqueo">Parqueo:</label>
            <input type="text" name="parqueo" value="<?php echo $visitante['parqueo']; ?>" required />
            
            
            
            <button type="submit">Actualizar</button>
        </form>
    </body>
    </html>
    <?php
} else {
    // Si no hay resultados, mostrar un mensaje
    echo "No se encontró al vigilante.";
}

// Cerrar la conexión
$conn->close();
?>
