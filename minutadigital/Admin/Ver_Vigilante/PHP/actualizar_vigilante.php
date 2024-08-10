<?php
// Conexión a la base de datos (reemplaza con tus propios detalles de conexión)
require_once 'db_connection.php'; // Incluye el archivo de conexión a la base de datos

// Obtener la cédula del vigilante a actualizar desde la URL
$cedula = $_GET['cedula'];

// Consulta SQL para obtener los datos del vigilante
$sql = "SELECT * FROM Vigilantes WHERE cedula = '$cedula'";
$result = $conn->query($sql);

// Verificar si se obtuvieron resultados de la consulta
if ($result !== false && $result->num_rows > 0) {
    // Obtener los datos del vigilante
    $vigilante = $result->fetch_assoc();

    // Mostrar el formulario de actualización
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="/minutadigital/Admin/Ver_Vigilante/CSS/actualizar_vigilante.css" />
        <title>Actualizar Vigilante</title>
    </head>
    <body>
        <div class="contenedor">
            <a href="/minutadigital/Admin/Ver_Vigilante/PHP/ver_vigilantes.php"><img class="img_admin_home" src="/minutadigital/Admin/Ver_Vigilante/IMG/imagenMinuta.jpg" /></a>
            
            <div class="div_minuta">
                <h1>Minuta de Vigilancia</h1>
                <h2>"Portal de la hacienda 1"</h2>
            </div>
        </div>

        <div>
            <h2 class="listado_usuarios">Actualizar Vigilante:</h2>
            <h2 class="admin">Administrador</h2>
        </div>

        <form action="procesar_actualizar_vigilante.php" method="post">
            <input type="hidden" name="cedula" value="<?php echo $vigilante['cedula']; ?>" />
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $vigilante['nombre']; ?>" required />
            
            <label for="apellido">Apellido:</label>
            <input type="text" name="apellido" value="<?php echo $vigilante['apellido']; ?>" required />
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $vigilante['email']; ?>" required />
            
            <label for="telefono">Teléfono:</label>
            <input type="tel" name="telefono" value="<?php echo $vigilante['telefono']; ?>" required />
            
            <label for="turno">Turno:</label>

            <select name="turno" required>
    <option value="Diurno" <?php echo ($vigilante['turno'] == 'Diurno') ? 'selected' : ''; ?>>Diurno</option>
    <option value="Nocturno" <?php echo ($vigilante['turno'] == 'Nocturno') ? 'selected' : ''; ?>>Nocturno</option>
</select>


            
            <label for="nombre_empresa">Nombre Empresa:</label>
            <input type="text" name="nombre_empresa" value="<?php echo $vigilante['nombre_empresa']; ?>" required />
            
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
