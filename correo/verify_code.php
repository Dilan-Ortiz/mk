<?php
session_start();

if (isset($_POST['verificar'])) {
    $codigoIngresado = trim($_POST['codigo']);
    $codigoGuardado = $_SESSION['code'] ?? null;

    if ($codigoIngresado == $codigoGuardado) {
        header("Location: changepass.php");
        exit();
    } else {
        echo "<script>alert('Código incorrecto. Intente de nuevo.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar código - Mortal Kombat</title>
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
        <h1>Verificación</h1>
        <h2>Ingrese el código enviado a su correo</h2>

        <form action="" method="POST">
            <input type="text" class="form-control mb-3" name="codigo" placeholder="Ingrese el código" required>
            <button type="submit" name="verificar" class="btn btn-mk w-100 py-2 text-white">Verificar</button>
        </form>
        <div class="acciones mt-3">
            <a href="correo.php" class="d-block">Volver</a>
        </div>
    </div>
</body>
</html>
