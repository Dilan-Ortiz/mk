<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

require_once("../config/database.php");
$db = new Database();
$con = $db->conectar();
session_start();

if (isset($_POST['enviar'])) {
    $elEmail = $_POST['email'];

    if (empty($elEmail)) {
        echo "<script>alert('El campo correo está vacío');</script>";
        die();
    }

    // Consultar si el correo existe
    $Cemail = $con->prepare("SELECT email FROM usuario WHERE email = :email");
    $Cemail->bindParam(":email", $elEmail);

    $Cemail->execute();
    $Cenviar = $Cemail->fetchColumn();

    // Obtener datos del usuario
    $user = $con->prepare("SELECT * FROM usuario WHERE email = :email");
    $user->bindParam(":email", $elEmail);
    $user->execute();
    $usuario = $user->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Generar un código aleatorio
        $numero_aleatorio = rand(1000, 9999);

        $_SESSION['usuario'] = $usuario['documento'];
        $_SESSION['code'] = $numero_aleatorio;

        if ($Cenviar) {
            // Configuración de PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'dilansantiortizm@gmail.com'; // tu correo
                $mail->Password   = 'lzjq nhvv fliv bvtn'; // usa contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipientes
                $mail->setFrom('dilansantiortizm@gmail.com', 'Dilan');
                $mail->addAddress($Cenviar);

                // Contenido
                $mail->isHTML(true);
                $mail->Subject = 'MORTAL KOMBAT - Reestablecer contraseña';
                $mail->Body    = "Su código para restablecer la contraseña es el siguiente: <b>" . $_SESSION['code'] . "</b>";
                $mail->AltBody = "Su código para restablecer la contraseña es: " . $_SESSION['code'];

                $mail->send();

                header("Location: verify_code.php");
                exit();
            } catch (Exception $e) {
                echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "<script>alert('Correo no encontrado');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortal Kombat Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: url(../img/correo.png) no-repeat center center fixed;
        background-size: cover;
        font-family: 'Mortal Claws', Arial, sans-serif;
    }


    .formulario {
        background: rgba(0, 0, 0, 0.8);
        box-shadow: 0 0 25px red;
        border-radius: 18px;
        padding: 2rem;
        width: 320px;
        text-align: center;
        color: #fff;
    }

    .formulario img {
        width: 110px;
        filter: drop-shadow(0 0 10px red);
        margin-bottom: 12px;
    }

    .formulario h1 {
        font-size: 30px;
        margin-bottom: 6px;
        color: #ffcc00;
        text-transform: uppercase;
        text-shadow: 0 0 10px red, 0 0 15px orange, 0 0 25px red;
        letter-spacing: 2px;
    }

    .formulario h2 {
        font-size: 18px;
        margin-bottom: 18px;
        text-shadow: 0 0 8px black, 0 0 15px red;
        font-weight: normal;
    }

    .form-control {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        border-radius: 10px;
    }
    .form-control::placeholder { color: #ccc; }

    .btn-mk {
        background: linear-gradient(45deg, red, orange);
        box-shadow: 0 0 15px red;
        font-weight: bold;
        text-transform: uppercase;
    }
    .btn-mk:hover {
        background: linear-gradient(45deg, orange, red);
        box-shadow: 0 0 25px yellow, 0 0 40px red;
    }

    .acciones a {
        color: #ffcc00;
        text-decoration: none;
        font-size: 14px;
    }
    .acciones a:hover {
        color: #fff;
        text-shadow: 0 0 12px red;
    }
    </style>
</head>
<body>
    <div class="formulario">
    <img src="../img/logo.png" alt="Logo Mortal Kombat">
    <h1>Mortal Kombat</h1>
    <h2>¿olvidaste tu contraseña?</h2>
    
    <form action="" method="POST">
        <input type="email" class="form-control mb-3" name="email" id="email" placeholder="ingrese su correo aqui" required>
        <button type="submit" name="enviar" id="enviar" class="btn btn-mk w-100 py-2 text-white">Enviar</button>
    </form>
      <div class="acciones mt-3">
        <a href="../index.html" class="d-block">volver</a>
      </div>
    </div>
</body>
</html>
