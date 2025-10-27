<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) die("Error de conexión: " . mysqli_connect_error());

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$documento = $_SESSION['documento'];


$id_partida = intval($_GET['id_partida'] ?? 0);
if ($id_partida <= 0) die("ID de partida inválido.");


$partida = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM partidas WHERE id_partida = $id_partida"));
if (!$partida) die("Partida no encontrada.");

$query_stats = mysqli_query($conexion, "
    SELECT u.username, a.avatar_foto, up.daño_realizado, up.eliminaciones, up.eliminado
    FROM usuario_partida up
    JOIN usuario_armas ua ON up.id_arma = ua.id_arma
    JOIN usuario u ON ua.documento = u.documento
    LEFT JOIN avatar a ON u.id_avatar = a.id_avatar
    WHERE up.id_partida = $id_partida
    ORDER BY up.daño_realizado DESC
");

$jugadores = [];
while ($row = mysqli_fetch_assoc($query_stats)) {
    $jugadores[] = $row;
}


$ganador = !empty($jugadores) ? $jugadores[0] : null;

mysqli_query($conexion, "
    UPDATE partidas 
    SET estado = 'finalizada', 
        fin = NOW(), 
        resultado = '".($ganador ? $ganador['username'] : 'Sin ganador')."'
    WHERE id_partida = $id_partida
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultados de la Partida #<?= $id_partida ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  margin: 0;
  padding: 0;
  background: url("img/nose.png") no-repeat center center fixed;
  background-size: cover;
  font-family: 'Poppins', sans-serif;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: brightness(0.7);
}

.container {
  max-width: 950px;
  width: 90%;
}

.card {
  background: rgba(0, 0, 0, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  box-shadow: 0 4px 25px rgba(0, 0, 0, 0.6);
  padding: 30px;
  text-align: center;
  color: #fff;
  backdrop-filter: blur(5px);
}

.winner {
  color: #00ffcc;
  font-size: 2.2rem;
  font-weight: 700;
  text-shadow: 0 0 10px #00ffcc;
  margin-bottom: 10px;
}

.no-winner {
  color: #ffd700;
  font-size: 1.5rem;
  margin-bottom: 25px;
  text-shadow: 0 0 10px #ffef99;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-size: 0.95rem;
}

.table th {
  background: rgba(255, 255, 255, 0.1);
  padding: 12px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  color: #00ffcc;
  letter-spacing: 0.5px;
}

.table td {
  padding: 10px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  color: #fff;
}

.table tr:hover {
  background: rgba(255, 255, 255, 0.05);
  transition: background 0.3s;
}

.btn-volver {
  background: linear-gradient(90deg, #00ffcc, #0099ff);
  border: none;
  color: #000;
  font-weight: bold;
  padding: 12px 35px;
  border-radius: 12px;
  margin-top: 30px;
  cursor: pointer;
  box-shadow: 0 0 15px rgba(0, 255, 200, 0.6);
  transition: all 0.3s ease;
}

.btn-volver:hover {
  background: linear-gradient(90deg, #00ccaa, #0077ff);
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(0, 255, 255, 0.9);
}

</style>
</head>
<body>

<div class="container">
  <h1 class="winner"> Resultado de la Partida #<?= $id_partida ?> </h1>

  <?php if ($ganador): ?>
    <h2 class="text-light">Ganador: <span style="color:#ff5555;"><?= htmlspecialchars($ganador['username']) ?></span></h2>
    <img src="../<?= htmlspecialchars($ganador['avatar_foto']) ?>" class="avatar" alt="Ganador">
  <?php else: ?>
    <h3 class="text-warning">No hubo ganador</h3>
  <?php endif; ?>

  <div class="card mt-4 p-4">
    <h4>Estadísticas de Jugadores</h4>
    <table class="table table-dark table-striped mt-3">
      <thead>
        <tr>
          <th>Jugador</th>
          <th>Daño Realizado</th>
          <th>Eliminaciones</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($jugadores as $j): ?>
          <tr>
            <td><?= htmlspecialchars($j['username']) ?></td>
            <td><?= intval($j['daño_realizado']) ?></td>
            <td><?= intval($j['eliminaciones']) ?></td>
            <td><?= $j['eliminado'] ? 'Eliminado' : 'Activo' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <a href="lobby.php?>" class="btn-volver">Volver </a>
</div>

</body>
</html>

$documento = $_SESSION['documento'];


$id_partida = intval($_GET['id_partida'] ?? 0);
if ($id_partida <= 0) die("ID de partida inválido.");


$partida = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM partidas WHERE id_partida = $id_partida"));
if (!$partida) die("Partida no encontrada.");

$query_stats = mysqli_query($conexion, "
    SELECT u.username, a.avatar_foto, up.daño_realizado, up.eliminaciones, up.eliminado
    FROM usuario_partida up
    JOIN usuario_armas ua ON up.id_arma = ua.id_arma
    JOIN usuario u ON ua.documento = u.documento
    LEFT JOIN avatar a ON u.id_avatar = a.id_avatar
    WHERE up.id_partida = $id_partida
    ORDER BY up.daño_realizado DESC
");

$jugadores = [];
while ($row = mysqli_fetch_assoc($query_stats)) {
    $jugadores[] = $row;
}


$ganador = !empty($jugadores) ? $jugadores[0] : null;

mysqli_query($conexion, "
    UPDATE partidas 
    SET estado = 'finalizada', 
        fin = NOW(), 
        resultado = '".($ganador ? $ganador['username'] : 'Sin ganador')."'
    WHERE id_partida = $id_partida
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultados de la Partida #<?= $id_partida ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  margin: 0;
  padding: 0;
  background: url("img/nose.png") no-repeat center center fixed;
  background-size: cover;
  font-family: 'Poppins', sans-serif;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  backdrop-filter: brightness(0.7);
}

.container {
  max-width: 950px;
  width: 90%;
}

.card {
  background: rgba(0, 0, 0, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 15px;
  box-shadow: 0 4px 25px rgba(0, 0, 0, 0.6);
  padding: 30px;
  text-align: center;
  color: #fff;
  backdrop-filter: blur(5px);
}

.winner {
  color: #00ffcc;
  font-size: 2.2rem;
  font-weight: 700;
  text-shadow: 0 0 10px #00ffcc;
  margin-bottom: 10px;
}

.no-winner {
  color: #ffd700;
  font-size: 1.5rem;
  margin-bottom: 25px;
  text-shadow: 0 0 10px #ffef99;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-size: 0.95rem;
}

.table th {
  background: rgba(255, 255, 255, 0.1);
  padding: 12px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  color: #00ffcc;
  letter-spacing: 0.5px;
}

.table td {
  padding: 10px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  color: #fff;
}

.table tr:hover {
  background: rgba(255, 255, 255, 0.05);
  transition: background 0.3s;
}

.btn-volver {
  background: linear-gradient(90deg, #00ffcc, #0099ff);
  border: none;
  color: #000;
  font-weight: bold;
  padding: 12px 35px;
  border-radius: 12px;
  margin-top: 30px;
  cursor: pointer;
  box-shadow: 0 0 15px rgba(0, 255, 200, 0.6);
  transition: all 0.3s ease;
}

.btn-volver:hover {
  background: linear-gradient(90deg, #00ccaa, #0077ff);
  transform: scale(1.05);
  box-shadow: 0 0 20px rgba(0, 255, 255, 0.9);
}

</style>
</head>
<body>

<div class="container">
  <h1 class="winner"> Resultado de la Partida #<?= $id_partida ?> </h1>

  <?php if ($ganador): ?>
    <h2 class="text-light">Ganador: <span style="color:#ff5555;"><?= htmlspecialchars($ganador['username']) ?></span></h2>
    <img src="../<?= htmlspecialchars($ganador['avatar_foto']) ?>" class="avatar" alt="Ganador">
  <?php else: ?>
    <h3 class="text-warning">No hubo ganador</h3>
  <?php endif; ?>

  <div class="card mt-4 p-4">
    <h4>Estadísticas de Jugadores</h4>
    <table class="table table-dark table-striped mt-3">
      <thead>
        <tr>
          <th>Jugador</th>
          <th>Daño Realizado</th>
          <th>Eliminaciones</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($jugadores as $j): ?>
          <tr>
            <td><?= htmlspecialchars($j['username']) ?></td>
            <td><?= intval($j['daño_realizado']) ?></td>
            <td><?= intval($j['eliminaciones']) ?></td>
            <td><?= $j['eliminado'] ? 'Eliminado' : 'Activo' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <a href="lobby.php?>" class="btn-volver">Volver </a>
</div>

</body>
</html>