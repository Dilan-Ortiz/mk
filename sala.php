<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "mk");


if (!$conexion) {
    die(" Error de conexión a la base de datos: " . mysqli_connect_error());
}

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$documento = $_SESSION['documento'];

if (!isset($_GET['mundo'])) {
    die("⚠️ No se seleccionó ningún mundo.");
}

$id_mundo = intval($_GET['mundo']);


$query_mundo = mysqli_query($conexion, "SELECT * FROM mundos WHERE id_mundo = $id_mundo");
if (mysqli_num_rows($query_mundo) == 0) {
    die("El mundo seleccionado no existe en la base de datos.");
}
$mundo = mysqli_fetch_assoc($query_mundo);

$query_salas = mysqli_query($conexion, "
    SELECT s.*, COUNT(su.documento) AS jugadores
    FROM salas s
    LEFT JOIN sala_usuarios su ON s.id_sala = su.id_sala
    WHERE s.id_mundo = $id_mundo AND s.estado = 'abierta'
    GROUP BY s.id_sala
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salas de <?= htmlspecialchars($mundo['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: url("img/mapas.png") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Orbitron', sans-serif;
        color: #fff;
        text-shadow: 0 0 10px rgba(0,0,0,0.8);
    }

    h1.title {
        color: #ff3b3b;
        text-align: center;
        font-size: 3rem;
        text-shadow: 0 0 20px #000, 0 0 10px #f00;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    p.description {
        text-align: center;
        color: #ffd;
        font-size: 1.2rem;
        max-width: 800px;
        margin: 0 auto 2rem;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px 20px;
        border-radius: 10px;
    }

    .btn-danger {
        background: linear-gradient(45deg, #f00, #900);
        border: none;
        box-shadow: 0 0 15px rgba(255,0,0,0.5);
        transition: all 0.2s ease;
    }

    .btn-danger:hover {
        background: linear-gradient(45deg, #ff5555, #b00);
        box-shadow: 0 0 25px rgba(255,0,0,0.8);
        transform: scale(1.05);
    }

    .card {
        background: rgba(0, 0, 0, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 25px rgba(255, 0, 0, 0.6);
    }

    .card-title {
        color: #ff4444;
        font-weight: bold;
        text-shadow: 0 0 8px #000;
    }

    .card-text {
        color: #ffd;
        text-shadow: 0 0 5px #000;
    }

    .alerta {
        text-align: center;
        color: #ffcc00;
        font-weight: bold;
        text-shadow: 0 0 10px #000;
        margin-top: 1.5rem;
        background: rgba(0, 0, 0, 0.4);
        display: inline-block;
        padding: 10px 20px;
        border-radius: 10px;
    }

    
    .container {
        background: rgba(0, 0, 0, 0.4);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 0 40px rgba(0,0,0,0.7);
    }
</style>

</head>
<body>

<div class="container mt-5">
    <h1 class="text-center text-danger mb-4 title"> Mundo: <?= htmlspecialchars($mundo['nombre']) ?></h1>
    <p class="text-center"><?= htmlspecialchars($mundo['descripcion']) ?></p>

    <div class="text-center mb-4">
        <a href="crear_sala.php?mundo=<?= $id_mundo ?>" class="btn btn-danger btn-lg"> Crear Nueva Sala</a>

    </div>

    <div class="row justify-content-center">
        <?php if (mysqli_num_rows($query_salas) > 0): ?>
            <?php while ($sala = mysqli_fetch_assoc($query_salas)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark text-light">
                        <div class="card-body text-center">
                            <h4 class="card-title"><?= htmlspecialchars($sala['nombre_sala']) ?></h4>
                            <p class="card-text">
                             Jugadores: <?= $sala['jugadores'] ?>/<?= $sala['max_jugadores'] ?><br>
                             Estado: <?= ucfirst($sala['estado']) ?>
                                </p>
                            <?php if ($sala['jugadores'] < $sala['max_jugadores']): ?>
                             <a href="unirse_sala.php?sala=<?= $sala['id_sala'] ?>" class="btn btn-danger">Unirse</a>
                                        <?php else: ?>
                               <button class="btn btn-secondary" disabled>Lleno</button>
                                     <?php endif; ?>

                               
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <h4 class="text-center text-warning">No hay salas disponibles</h4>
        <?php endif; ?>
    </div>
</div>

</body>
</html>