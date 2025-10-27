<?php
session_start();


$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}


if (!isset($_GET['mundo'])) {
    die("No se seleccionó ningún mundo.");
}
$id_mundo = intval($_GET['mundo']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $max_jugadores = intval($_POST['max_jugadores']);

    
    $sql = "INSERT INTO salas (id_mundo, nombre_sala, estado, max_jugadores)
            VALUES ($id_mundo, '$nombre', 'abierta', $max_jugadores)";
    if (mysqli_query($conexion, $sql)) {
        header("Location: paginas/sala.php?mundo=$id_mundo");
        exit;
    } else {
        echo "Error al crear la sala: " . mysqli_error($conexion);
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