<?php
require_once 'db_connection.php';
session_start();

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    $sql = "SELECT * FROM Usuarios WHERE cedula_usuario = '$usuario' AND contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tipoUsuario = $row["rol"];

        $_SESSION["nombre_usuario"] = $row["nombre_usuario"];
        $_SESSION["cedula_usuario"] = $row["cedula_usuario"];

        switch ($tipoUsuario) {
            case "admin":
                header("Location: /minutadigital/Admin/admin_home/HTML/admin_home.html");
                break;
            case "residente":
                header("Location:/minutadigital/Residente/Residente_home/PHP/Residente_home.php");
                break;
            case "vigilante":
                header("Location: /minutadigital/Vigilante/Vigilante_Home/HTML/Vigilante.html");
                break;

            default:
                $mensajeError = "Error: Tipo de usuario no reconocido";
                break;
        }
      } else {
        echo "<script>";
        echo "alert('Contraseña o Usuario Incorrecto.');"; 
        echo "window.location.href = '../PHP/login.php';"; 
        echo "</script>";
        exit; 
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>MINUTA DIGITAL</title>
  <meta name="Minuta Digital" content="Minuta del conjunto cerrado, portal de la hacienda 1" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="/minutadigital/Login/CSS/login.css" />
  

</head>
<body>
  <main>
    <div>
      <div>
        <figure>
          <a href="/minutadigital/Home/HTML/home.html">
            <img class="img_home" src="/minutadigital/Login/IMG/imagenMinuta.jpg" alt="imagen home" />
          </a>
        </figure>
        <div class="title_subtitle">
          <h1 class="title">MINUTA DE VIGILANCIA</h1>
          <h2 class="subtitle">"Portal de la Hacienda 1"</h2>
        </div>
        <form action="" method="POST" autocomplete="off">
  
          <div class="container">
        <div class="card">
            <a class="login">Inicio de Sesión</a>
            <div class="inputBox">
                <input name="usuario" type="text" required="required">
                <span class="user">Cedula</span>
            </div>

            <div class="inputBox">
                <input name="contrasena" type="password" required="required">
                <span>Contraseña</span>
            </div>

            <button class="enter">Ingresar</button>

        </div>
    </div>
        </form>

        <!-- Mostrar mensaje de error si existe -->
      
      </div>
    </div>
  </main>
</body>
</html>
