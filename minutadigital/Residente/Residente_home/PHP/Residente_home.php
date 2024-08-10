<?php
session_start();

// Verificar si la cédula está definida en la sesión
if (!isset($_SESSION['cedula_usuario'])) {
    // Si la cédula no está definida en la sesión, redirigir al usuario al formulario de inicio de sesión
    header("Location: login.php");
    exit();
}

$cedula_residente = $_SESSION['cedula_usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Formulario de Correspondencia</title>
    <meta name="Formulario de Correspondencia" content="Formulario para correspondencia" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/minutadigital/Residente/Residente_home/CSS/residente_home.css" />
</head>

<body>
    <main>
        <header>
            <figure>
                <a href="/minutadigital/Home/HTML/home.html">
                    <img class="img_home" src="/minutadigital/Residente/Residente_home/IMG/imagenMinuta.jpg" alt="imagen home" />
                </a>
            </figure>
            <div class="titulo">
                <h1 class="titulo_principal">MINUTA DE VIGILANCIA</h1>
                <h2 class="subtitulo">"Portal de la Hacienda 1"</h2>
            </div>
            <div class="titulo">
                <h1 class="titulo_principal">Correspondencia</h1>
            </div>
        </header>
        <div class="contenedor">
            <form id="formularioVigilante" action="" method="POST">
                <br />
                <div class="container_button">
                    <a href="/minutadigital/Residente/VerificarCorrespondencia/PHP/verificar_corres.php">
                        <button type="button" class="btn secondary">Correspondencia</button>
                    </a>
                    <a href="/minutadigital/Residente/Eventos/PHP/eventos_crud.php">
                        <button type="button" class="btn primary">Eventos</button>
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
