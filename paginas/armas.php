<?php
session_start();
require_once '../config/database.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT * FROM armas");
$sql->execute();
$resultados12 = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Weapon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: radial-gradient(#291111, #000);
        font-family: 'Trebuchet MS', sans-serif;
    }

    .weapon-container {
        background: rgba(30, 30, 30, 0.9);
        border: 4px solid #a00;
        border-radius: 10px;
        box-shadow: 0px 0px 25px rgba(255, 0, 0, 0.6);
        padding: 30px;
    }

    .title {
        color: #ff0000;
        text-align: center;
        margin-bottom: 25px;
        text-shadow: 2px 2px 6px #000;
        font-size: 1.8rem;
        letter-spacing: 1px;
    }

    .weapon-grid {
        display: grid;
        grid-template-columns: repeat(4, 170px);
        gap: 20px;
        justify-content: center;
    }

    .card-dark {
        background: linear-gradient(180deg, #2b2b2b, #1a1a1a);
        border: 2px solid #660000;
        border-radius: 8px;
        box-shadow: 0px 0px 10px #000;
        transition: all 0.25s ease-in-out;
        text-align: center;
        color: #ddd;
    }

    .card-dark:hover {
        transform: scale(1.15);
        border-color: #ff0000;
        box-shadow: 0px 0px 20px #ff0000;
    }

    .card-dark img {
        width: 100%;
        height: 90px;
        object-fit: contain;
        border-bottom: 2px solid #550000;
    
    }

    .card-dark .card-body {
        padding: 8px;
    }

    .card-dark .card-title {
        font-size: 0.8rem;
        margin: 3px 0;
        color: #ff4444;
        text-shadow: 1px 1px 3px #000;
    }

    .btn-ff {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 180px;
        padding: 12px 18px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        color: #fff;
        border: none;
        border-radius: 6px;
        background: linear-gradient(90deg, #2b2b2b, #1c1c1c);
        box-shadow: 0 4px 8px rgba(0,0,0,0.6);
        transition: all 0.2s ease;
        margin: 25px auto 0;
    }

    .btn-ff:hover {
        transform: scale(1.05);
        background: linear-gradient(90deg, #790d0dff, #2c080bff);
        box-shadow: 0 0 20px #ff0000;
    }


    .card-dark {
        position: relative;
        background: linear-gradient(180deg, #2b2b2b, #1a1a1a);
        border: 2px solid #660000;
        border-radius: 8px;
        box-shadow: 0px 0px 10px #000;
        transition: all 0.25s ease-in-out;
        text-align: center;
        color: #ddd;
    }

    .card-dark:hover {
        transform: scale(1.15);
        border-color: #ff0000;
        box-shadow: 0px 0px 20px #ff0000;
    }
    
    .card-dark.bloqueada:hover {
        transform: none;
        border-color: #660000;
        box-shadow: 0px 0px 10px #000;
    }

    .capa-bloqueo {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        border-radius: 8px; 
        
        display: flex; 
        flex-direction: column;
        justify-content: center;
        align-items: center;
        
        color: #ff4444;
        text-shadow: 0 0 5px #000;
        font-weight: bold;
        
        pointer-events: auto; 
        cursor: default; 
    }

    .candado {
        font-size: 2.5em;
        margin-bottom: 5px;
        line-height: 1;
    }

    .nivel-texto {
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
    }
    </style>
</head>
    </style>
</head>
<body>

    <div class="weapon-container">
        <h1 class="title">ARMAS</h1>
        <div class="weapon-grid">
            <?php 
            $documento_usuario = $_SESSION['documento'];
            $sql_usuario = $con->prepare("SELECT id_nivel FROM usuario WHERE documento = :doc");
            $sql_usuario->bindParam(':doc', $documento_usuario);
            $sql_usuario->execute();
            $usuario = $sql_usuario->fetch(PDO::FETCH_ASSOC);

            $nivel_usuario_actual = $usuario['id_nivel'] ?? 0;
            foreach ($resultados12 as $resultado): 
                $nivel_requerido = $resultado['id_nivel'];
                $esta_bloqueada = $nivel_usuario_actual < $nivel_requerido;
            ?>
                <div class="card-dark <?php echo $esta_bloqueada ? 'bloqueada' : ''; ?>">
                    
                    <img src="../<?php echo $resultado['imagen_url']; ?>" alt="<?php echo $resultado['nombre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $resultado['nombre']; ?></h5>
                        <h5 class="card-title">Daño: <?php echo $resultado['daño']; ?></h5> 
                    </div>

                    <?php if ($esta_bloqueada): ?>
                        <div class="capa-bloqueo">
                            <span class="candado"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
</svg></span>
                            <p class="nivel-texto">Nivel <?php echo $nivel_requerido; ?> requerido</p>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>
        </div>
        <button class="btn-ff" onclick="window.location.href='../lobby.php'">Volver</button>
    </div>

</body>
</html>
