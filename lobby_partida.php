<?php
session_start();
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$documento = $_SESSION['documento'];

// Validar sala seleccionada
if (!isset($_GET['sala'])) {
    die("No se seleccionó ninguna sala.");
}

$id_sala = intval($_GET['sala']);

// Obtener datos de la sala
$sql_sala = $con->prepare("SELECT * FROM salas WHERE id_sala = :id_sala");
$sql_sala->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql_sala->execute();
$sala = $sql_sala->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    die("La sala no existe.");
}

// Verificar si el usuario pertenece a la sala
$sql_check = $con->prepare("SELECT * FROM sala_usuarios WHERE documento = :documento AND id_sala = :id_sala");
$sql_check->bindParam(':documento', $documento);
$sql_check->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql_check->execute();

if ($sql_check->rowCount() == 0) {
    die("No perteneces a esta sala.");
}

// Validar que los jugadores sean del mismo nivel
$sql_nivel_existente = $con->prepare("
    SELECT u.id_nivel 
    FROM sala_usuarios su
    INNER JOIN usuario u ON su.documento = u.documento 
    WHERE su.id_sala = :id_sala 
    LIMIT 1
");
$sql_nivel_existente->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql_nivel_existente->execute();
$nivel_existente = $sql_nivel_existente->fetch(PDO::FETCH_ASSOC);

if ($nivel_existente) {
    $nivel_jugadores = $nivel_existente['id_nivel'];

    $sql_nivel_usuario = $con->prepare("SELECT id_nivel FROM usuario WHERE documento = :documento");
    $sql_nivel_usuario->bindParam(':documento', $documento);
    $sql_nivel_usuario->execute();
    $nivel_usuario = $sql_nivel_usuario->fetch(PDO::FETCH_ASSOC);

    if ($nivel_usuario && $nivel_usuario['id_nivel'] != $nivel_jugadores) {
        die("<script>
            alert('No puedes entrar. Los jugadores de esta sala son de otro nivel.');
            window.location.href = 'seleccionar_sala.php';
        </script>");
    }
}

// Consultar jugadores y su estado “listo”
$sql_jugadores = $con->prepare("
    SELECT u.username, a.avatar_foto, su.listo, su.documento
    FROM sala_usuarios su
    INNER JOIN usuario u ON su.documento = u.documento
    INNER JOIN avatar a ON u.id_avatar = a.id_avatar
    WHERE su.id_sala = :id_sala
");
$sql_jugadores->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql_jugadores->execute();
$jugadores = $sql_jugadores->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Lobby - <?= htmlspecialchars($sala['nombre_sala']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
  backdrop-filter: brightness(0.9);
}

.lobby-container {
  background: rgba(20, 20, 30, 0.8);
  border-radius: 20px;
  padding: 40px 50px;
  width: 850px;
  box-shadow: 0 0 25px rgba(255, 0, 0, 0.3);
  border: 1px solid rgba(255, 0, 43, 0.5);
  text-align: center;
}

h1 {
  color: #ff0000ff;
  font-size: 2.5rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 10px;
  text-shadow: 0 0 20px #ff000dff;
}

p { color: #ddd; }

.avatar-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 25px;
  margin-top: 20px;
}

.avatar-card {
  width: 120px;
  text-align: center;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.avatar-card:hover {
  transform: scale(1.1);
  box-shadow: 0 0 15px rgba(255, 0, 0, 0.6);
}

.avatar-card img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 2px solid #ff0000ff;
  object-fit: cover;
}

.avatar-card span {
  display: block;
  margin-top: 8px;
  font-weight: 600;
  color: #fff;
}

.estado-listo {
  font-size: 0.9rem;
  color: #0f0;
}

.estado-no {
  font-size: 0.9rem;
  color: #aaa;
}

.btn-listo {
  background: linear-gradient(90deg, #ff004cff, #ff0077ff);
  border: none;
  font-size: 1.1rem;
  padding: 10px 30px;
  border-radius: 10px;
  margin-top: 25px;
  color: white;
  cursor: pointer;
  box-shadow: 0 0 20px rgba(255, 0, 0, 0.4);
  transition: 0.3s;
}

.btn-listo:hover {
  background: linear-gradient(90deg, #ff0040ff, #ff0000ff);
  transform: scale(1.08);
  box-shadow: 0 0 25px rgba(255, 0, 0, 0.7);
}

.btn-salir {
  background: linear-gradient(90deg, #444, #222);
  border: none;
  font-size: 1.1rem;
  padding: 10px 25px;
  border-radius: 8px;
  margin-top: 20px;
  color: #ccc;
  cursor: pointer;
  transition: 0.3s;
}

.btn-salir:hover {
  background: linear-gradient(90deg, #666, #333);
  color: white;
}
</style>

<script>
// Actualiza el lobby periódicamente y verifica si debe iniciar
function actualizarLobby() {
  $("#jugadores").load("lobby_jugadores.php?sala=<?= $id_sala ?>", function () {
    $.get("check_inicio.php?sala=<?= $id_sala ?>", function (data) {
      if (data.trim() === "start") {
        window.location.href = "iniciar_partida.php?id_sala=<?= $id_sala ?>";
      }
    });
  });
}

// Marcar jugador como listo
function marcarListo() {
  $.post("actualizar_estado_listo.php", { id_sala: <?= $id_sala ?> }, function() {
    actualizarLobby();
  });
}

// Refrescar cada 3 segundos
setInterval(actualizarLobby, 3000);
</script>
</head>

<body>
<div class="lobby-container">
  <h1 class="text-danger mb-3"><?= htmlspecialchars($sala['nombre_sala']) ?></h1>
  <p>Listos para la guerra... que gane el mejor</p>

  <div id="jugadores" class="avatar-grid">
    <?php foreach ($jugadores as $jugador): ?>
      <div class="avatar-card">
        <img src="/MK/<?= htmlspecialchars($jugador['avatar_foto']) ?>" alt="Avatar">
        <span><?= htmlspecialchars($jugador['username']) ?></span>
        <?php if ($jugador['listo']): ?>
          <div class="estado-listo">Listo</div>
        <?php else: ?>
          <div class="estado-no">Esperando...</div>
        <?php endif; ?>
      </div>  
    <?php endforeach; ?>
  </div>

  <button class="btn-listo" onclick="marcarListo()">Estoy Listo</button>

  <form action="salir_sala.php" method="POST">
    <input type="hidden" name="id_sala" value="<?= $id_sala ?>">
    <input type="hidden" name="id_mundo" value="<?= $sala['id_mundo'] ?>">
    <button class="btn-salir">Abandonar partida</button>
  </form>
</div>
</body>
</html>
