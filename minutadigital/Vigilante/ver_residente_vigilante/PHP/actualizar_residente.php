<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

// Obtener la cédula del residente a actualizar desde la URL
$cedula = $_GET['cedula'];

// Consulta SQL para obtener los datos del residente
$sql = "SELECT * FROM Residentes WHERE cedula = '$cedula'";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados de la consulta
if ($result !== false && $result->num_rows > 0) {
    // Obtener los datos del residente
    $residente = $result->fetch_assoc();

    // Mostrar el formulario de actualización
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="/minutadigital/Vigilante/ver_residente_vigilante/CSS/actualizar_residente.css" />
        <title>Actualizar Residente</title>
    </head>
    <body>
        <div class="contenedor">
            <a href="/minutadigital/Vigilante/ver_residente_vigilante/PHP/ver_residente.php"><img class="img_admin_home" src="/minutadigital/Vigilante/ver_residente_vigilante/IMG/imagenMinuta.jpg" /></a>
            <div class="div_minuta">
                <h1>Minuta de Vigilancia</h1>
                <h2>"Portal de la hacienda 1"</h2>
            </div>
        </div>

        <div>
            <h2 class="listado_usuarios">Actualizar Residente:</h2>
            <h2 class="admin">Administrador</h2>
        </div>

        <form action="procesar_actualizar_residente.php" method="post">
            <input type="hidden" name="cedula" value="<?php echo $residente['cedula']; ?>" />
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $residente['nombre']; ?>" required />
            
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" value="<?php echo $residente['apellido']; ?>" required />
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $residente['email']; ?>" required />
            
            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" value="<?php echo $residente['telefono']; ?>" required />
            
            <label for="num_apto">Número Apto:</label>
            <input type="text" name="num_apto" value="<?php echo $residente['num_apto']; ?>" required />
            
            <label for="parqueo">Parqueo:</label>
            <input type="text" name="parqueo" value="<?php echo $residente['parqueo']; ?>" required />
            
            <button type="submit">Actualizar</button>
        </form>
    </body>
    </html>
    <?php
} else {
    // Si no hay resultados, mostrar un mensaje
    echo "No se encontró al residente.";
}

// Cerrar la conexión
$conn->close();
?>
