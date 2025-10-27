<?php
session_start();
require_once '../config/database.php'; 

$db = new Database();
$con = $db->conectar();

$username = $_SESSION['username'];
$doc = $_SESSION['documento'];

$sqlmundos = $con->prepare("SELECT * FROM mundos");
$sqlmundos->execute();
$mundos = $sqlmundos->fetchAll(PDO::FETCH_ASSOC);


$sqljugadores = $con->prepare("
    SELECT * 
    FROM usuario 
    WHERE id_nivel = (
        SELECT id_nivel FROM usuario WHERE documento = ?
    ) 
    AND documento != ?
");
$sqljugadores->execute([$doc, $doc]);
$jugadores = $sqljugadores->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby - Selección de Mundo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" rel="stylesheet">

    <style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        background: radial-gradient(#291111, #000);
        color: #fff;
        font-family: 'Arial', sans-serif;
    }

    .weapon-container {
        background: rgba(50, 50, 50, 0.9);
        border: 4px solid #aaa;
        border-radius: 8px;
        box-shadow: 0px 0px 20px rgba(255, 0, 0, 0.6);
        padding: 20px;
        width: 90%;
        max-width: 1200px;
        margin-top: 40px;
    }

    .title {
        color: #c00;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px #000;
    }

    .card-img-top {
        border: 2px solid #666;
        border-radius: 6px;
        box-shadow: 0px 0px 10px #000;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden; 
    }

    .card-img-top:hover {
        border: 2px solid #f00;
        box-shadow: 0px 0px 15px #f00;
        transform: scale(1.10);
    }

    .info-container {
        text-align: center;
        margin-bottom: 30px;
    }

    .info-container img {
        border-radius: 10px;
        border: 3px solid #f00;
        box-shadow: 0px 0px 10px #f00;
        width: 120px;
        height: 120px;
        object-fit: cover;
    }

    .players-list, .worlds-list {
        margin-top: 20px;
    }

    .players-list li {
        list-style: none;
        color: #fff;
        background: rgba(255,255,255,0.1);
        margin: 5px 0;
        padding: 8px;
        border-radius: 5px;
    }
    </style>
</head>
<body>


    <div class="weapon-container worlds-list">
        <h1 class="title"> Selecciona un Mundo</h1>
        <div class="d-flex flex-wrap gap-4 m-4 justify-content-center">
    <?php foreach ($mundos as $mundo): ?>
        <div class="card-dark" style="width: 18rem;">
            <a href="sala.php?mundo=<?php echo $mundo['id_mundo']; ?>">
                <img src="/mk/<?php echo htmlspecialchars($mundo['img_mapa']); ?>" class="card-img-top" alt="Mapa">
            </a>
            <div class="card-body text-center">
                <h5 class="card-title text-danger"><?php echo htmlspecialchars($mundo['nombre']); ?></h5>
            </div>
        </div>
    <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
