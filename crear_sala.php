<?php
session_start();
require_once("config/database.php"); // Usa tu clase Database

// Conectar a la base de datos con PDO
$db = new Database();
$con = $db->conectar();

// Verificar que se reciba el parámetro 'mundo'
if (!isset($_GET['mundo'])) {
    die("No se seleccionó ningún mundo.");
}

$id_mundo = intval($_GET['mundo']);

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $max_jugadores = intval($_POST['max_jugadores']);

    try {
        // Insertar nueva sala usando consulta preparada
        $sql = $con->prepare("
            INSERT INTO salas (id_mundo, nombre_sala, estado, max_jugadores)
            VALUES (?, ?, 'abierta', ?)
        ");
        $sql->execute([$id_mundo, $nombre, $max_jugadores]);

        // Redirigir al listado o vista de la sala
        header("Location: paginas/sala.php?mundo=$id_mundo");
        exit;

    } catch (PDOException $e) {
        echo "Error al crear la sala: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Sala</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: radial-gradient(#111, #000);
    color: white;
    text-align: center;
    padding-top: 80px;
}
.container {
    background: rgba(30, 30, 30, 0.9);
    padding: 30px;
    border-radius: 10px;
    width: 400px;
    margin: auto;
    box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
}
</style>
</head>
<body>

<div class="container">
    <h2 class="text-danger">Crear Nueva Sala</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nombre de la sala</label>
            <input type="text" name="nombre" class="form-control text-center" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Máx. jugadores</label>
            <input type="number" name="max_jugadores" min="5" max="5" class="form-control text-center" value="5" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Crear Sala</button>
        <a href="sala.php?mundo=<?= $id_mundo ?>" class="btn btn-secondary w-100 mt-2">Cancelar</a>
    </form>
</div>

</body>
</html>
