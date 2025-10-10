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
    </style>
</head>
<body>

    <div class="weapon-container">
        <h1 class="title">Selecciona tu arma</h1>
        <div class="weapon-grid">
            <?php foreach ($resultados12 as $resultado): ?>
                <div class="card-dark">
                    <img src="../<?php echo $resultado['imagen_url']; ?>" alt="<?php echo $resultado['nombre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $resultado['nombre']; ?></h5>
                        <h5 class="card-title">Daño: <?php echo $resultado['daño']; ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="btn-ff" onclick="window.location.href='../lobby.php'">Volver</button>
    </div>

</body>
</html>
