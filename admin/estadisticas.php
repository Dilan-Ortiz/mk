<?php
$conexion = new mysqli("localhost", "root", "", "mk");
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}


$sql = "SELECT u.username, u.puntos_actuales, u.id_nivel, n.nombre AS nivel, e.nombre AS estado, u.ultimo_login
        FROM usuario u
        JOIN estados e ON u.id_estado = e.id_estado
        JOIN niveles n ON u.id_nivel = n.id_nivel
        WHERE u.id_estado = 1";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Jugadores</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url("../img/administrador.jpg") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .navbar {
            height: 150px;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            color: white;
            text-shadow: 2px 2px 6px #000;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            padding-top: 200px;
        }

        .card {
            width: 280px;
            height: 260px;
            background-color: rgba(0, 0, 0, 0.85);
            border-radius: 15px;
            box-shadow: 0 0 15px #ff0000;
            color: white;
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px #ff4444;
        }

        .card h3 {
            font-size: 1.5rem;
            color: #ff4d4d;
            text-shadow: 1px 1px 4px #000;
        }

        .card p {
            margin: 8px 0;
            font-size: 1rem;
        }

        .volver {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background-color: #e63946;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            color: white;
            font-weight: bold;
            box-shadow: 0 0 10px #000;
            text-decoration: none;
            transition: 0.3s;
        }

        .volver:hover {
            background-color: #c1121f;
            transform: scale(1.05);
        }

    </style>
</head>
<body>

    <div class="navbar">
        <h1> Estadísticas de Jugadores Activos</h1>
    </div>

    <div class="card-container">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($fila['username']); ?></h3>
                    <p><strong>Puntos actuales:</strong> <?php echo htmlspecialchars($fila['puntos_actuales']); ?></p>
                    <p><strong>Nivel:</strong> <?php echo htmlspecialchars($fila['nivel']); ?></p>
                    <p><strong>Estado:</strong> <?php echo htmlspecialchars($fila['estado']); ?></p>
                    <p><strong>Último ingreso:</strong><br><?php echo htmlspecialchars($fila['ultimo_login']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:white; text-align:center;">No hay jugadores activos actualmente.</p>
        <?php endif; ?>
    </div>

    <a href="admin.php" class="volver"> Regresar</a>

</body>
</html>
