<?php
require_once("../config/database.php");
$db = new Database();
$con = $db->conectar();
session_start();

if (isset($_POST["enviar"])) {
    $contrasena = $_POST["new_contrasefia"];
    $contrasena_Verify = $_POST["confirmar_con"];

    // Validación de campos vacíos
    if (empty($contrasena) || empty($contrasena_Verify)) {
        echo "<script>alert('DATOS VACÍOS');</script>";
    }
    // Validación de formato (solo letras y números)
    else if (!preg_match("/^[a-zA-Z0-9]+$/", $contrasena)) {
        echo "<script>alert('La contraseña solo puede contener letras y números.');</script>";
    }
    else {
        // Encriptar la contraseña
        $encripted = password_hash($contrasena, PASSWORD_BCRYPT, array("cost" => 12));

        // Verificar que coincidan
      if ($contrasena === $contrasena_Verify) {
    $sql = $con->prepare("UPDATE usuario SET password = :password WHERE documento = :usuario");
    $sql->bindParam(":password", $encripted, PDO::PARAM_STR);
    $sql->bindParam(":usuario", $_SESSION['usuario'], PDO::PARAM_STR);
    $sql->execute();

    header("Location: destruir_contraseña.php");
    exit();
} else {
    echo "<script>alert('CONTRASEÑAS DESIGUALES');</script>";
}

    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="controller/css/style.css">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" rel="stylesheet">

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

    .datos {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        border-radius: 10px;
    }
    .datos::placeholder { color: #ccc; }

    .continuar {
        background: linear-gradient(45deg, red, orange);
        box-shadow: 0 0 15px red;
        font-weight: bold;
        text-transform: uppercase;
    }
    .continuar:hover {
        background: linear-gradient(45deg, orange, red);
        box-shadow: 0 0 25px yellow, 0 0 40px red;
    }
    </style>
</head>
<body>
    <div class="login-box">
    
        <form action="" method="POST" enctype="multipart/form-data" class="formulario">
        <img src="../img/logo.png" class="avatar" alt="Imagen Avatar">
        <h1>CAMBIAR CONTRASEÑA</h1>
        <label for="new_contrasefia">Nueva Contraseña:</label>
        <input type="password" class="datos mb-3" id="new_contrasefia" name="new_contrasefia" required>
        <span></span>

        <label for="confirmar_con">Confirmar Contraseña:</label>
        <input type="password" class="datos mb-3" id="confirmar_con" name="confirmar_con" required>
        <span></span>

        <div class="botones mt-3">
        <button type="submit" class="continuar btn w-100 py-2 text-white" name="enviar">Cambiar</button>
        </div>
    </form>
    </div>
</body>
</html>