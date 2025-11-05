<?php
session_start();
require_once("config/database.php");
$db = new Database();
$conexion = $db->conectar();

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$mi_documento = $_SESSION['documento'];
$id_partida = intval($_GET['id_partida'] ?? 0);
if ($id_partida <= 0) die("Partida no especificada.");

// Obtener información actual de la partida
$sql = $conexion->prepare("SELECT * FROM partidas WHERE id_partida = ?");
$sql->execute([$id_partida]);
$partida = $sql->fetch(PDO::FETCH_ASSOC);
if (!$partida) die("Partida no encontrada.");

// Si no hay inicio, se establece
if (empty($partida['inicio'])) {
    $update = $conexion->prepare("UPDATE partidas SET inicio = NOW(), estado = 'en_curso', duracion_segundos = 300 WHERE id_partida = ?");
    $update->execute([$id_partida]);
    $sql->execute([$id_partida]);
    $partida = $sql->fetch(PDO::FETCH_ASSOC);
}

// Calcular tiempo restante
$inicio = strtotime($partida['inicio']);
$duracion = $partida['duracion_segundos'] ?: 300;
$tiempo_restante = max(0, $duracion - (time() - $inicio));

// 🔹 AJAX (actualiza vidas/puntos en tiempo real)
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $sql = $conexion->prepare("
        SELECT up.documento, u.username, a.avatar_foto, up.vida_restante, up.puntos_acumulados, up.eliminado
        FROM usuario_partida up
        JOIN usuario u ON up.documento = u.documento
        LEFT JOIN avatar a ON u.id_avatar = a.id_avatar
        WHERE up.id_partida = ?
        ORDER BY up.puntos_acumulados DESC
    ");
    $sql->execute([$id_partida]);
    $jugadores = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si ya debe finalizar la partida
    $sqlCount = $conexion->prepare("SELECT COUNT(*) FROM usuario_partida WHERE id_partida = ? AND eliminado = 0");
    $sqlCount->execute([$id_partida]);
    $activos = (int)$sqlCount->fetchColumn();

    if ($activos <= 1 || $tiempo_restante <= 0) {
        echo "FINALIZADA";
        exit;
    }

    // Renderizar tablero actualizado
    foreach ($jugadores as $p) {
        $vidaPorcentaje = max(0, min(100, ($p['vida_restante'] / 10)));
        echo '<div class="col-6 mb-3">
                <div class="d-flex gap-2 align-items-center">
                    <img src="/mk/' . htmlspecialchars($p['avatar_foto']) . '" class="avatar">
                    <div>
                        <b>' . htmlspecialchars($p['username']) . '</b>
                        <div class="small">Vida: ' . max(0, $p['vida_restante']) . '/1000</div>
                        <div class="health"><div style="width:' . $vidaPorcentaje . '%"></div></div>
                        <div class="small">Puntos: ' . intval($p['puntos_acumulados']) . '</div>
                        <div class="small">Eliminado: ' . ($p['eliminado'] ? 'Sí' : 'No') . '</div>
                    </div>
                </div>
            </div>';
    }
    exit;
}

// Obtener jugadores iniciales
$sql = $conexion->prepare("
    SELECT up.documento, u.username, a.avatar_foto, up.vida_restante, up.puntos_acumulados, up.eliminado
    FROM usuario_partida up
    JOIN usuario u ON up.documento = u.documento
    LEFT JOIN avatar a ON u.id_avatar = a.id_avatar
    WHERE up.id_partida = ?
    ORDER BY up.puntos_acumulados DESC
");
$sql->execute([$id_partida]);
$jugadores_res = $sql->fetchAll(PDO::FETCH_ASSOC);

// Obtener armas del nivel del usuario
$sql = $conexion->prepare("SELECT id_nivel FROM usuario WHERE documento = ?");
$sql->execute([$mi_documento]);
$nivel = $sql->fetchColumn();

$sql = $conexion->prepare("SELECT id_arma, nombre, daño, tipo FROM armas WHERE id_nivel <= ?");
$sql->execute([$nivel]);
$armas_res = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Partida #<?= $id_partida ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:radial-gradient(#111,#000);color:#fff;font-family:Poppins,sans-serif;}
.container{max-width:1100px;margin-top:24px;}
.player-card{background:rgba(20,20,20,0.8);padding:12px;border-radius:10px;border:1px solid rgba(255,0,0,0.15);}
.avatar{width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #ff3333;}
.health{height:12px;background:#333;border-radius:6px;overflow:hidden;margin-top:8px;}
.health>div{height:100%;background:linear-gradient(90deg,#0f0,#ff0,#f00);}
.small{font-size:0.9rem;color:#ddd;}
.btn-attack{background:linear-gradient(90deg,#ff004c,#ff0033);border:none;color:#fff;padding:10px 20px;border-radius:8px;}
#timer{font-weight:700;color:#ffdddd;font-size:1.3rem;text-align:center;}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let tiempo = <?= $tiempo_restante ?>;

// 🔹 Refrescar estado de jugadores cada segundo
function refreshStatus(){
  $.get("partida.php?id_partida=<?= $id_partida ?>&ajax=1", function(data){
    if(data.trim() === "FINALIZADA"){
      window.location.href = "finalizar_partida.php?id_partida=<?= $id_partida ?>";
    } else {
      $("#playerList").html(data);
    }
  });
}

setInterval(refreshStatus, 1000);

// 🔹 Temporizador visual
function actualizarTimer() {
  if (tiempo <= 0) {
    clearInterval(timerInterval);
    window.location.href = "finalizar_partida.php?id_partida=<?= $id_partida ?>";
  } else {
    const min = String(Math.floor(tiempo / 60)).padStart(2, '0');
    const seg = String(tiempo % 60).padStart(2, '0');
    document.getElementById('timer').textContent = `⏳ ${min}:${seg}`;
    tiempo--;
  }
}
const timerInterval = setInterval(actualizarTimer, 1000);
</script>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3>Partida #<?= $id_partida ?></h3>
      <div class="small">Estado: <?= htmlspecialchars($partida['estado']) ?></div>
    </div>
    <div id="timer">⏳ Cargando...</div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card player-card">
        <h5>Tu ataque</h5>
        <form id="formAtaque" method="POST">
          <input type="hidden" name="id_partida" value="<?= $id_partida ?>">
          <div class="mb-2">
            <label class="small">Arma</label>
            <select name="id_arma" class="form-select" required>
              <?php foreach ($armas_res as $a): ?>
                <option value="<?= $a['id_arma'] ?>"><?= htmlspecialchars($a['nombre']) ?> (<?= $a['daño'] ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="small">Objetivo</label>
            <select name="objetivo" class="form-select" required>
              <?php foreach ($jugadores_res as $p): ?>
                <?php if ($p['documento'] == $mi_documento || $p['eliminado']) continue; ?>
                <option value="<?= $p['documento'] ?>"><?= htmlspecialchars($p['username']) ?> — Vida: <?= max(0, $p['vida_restante']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button class="btn-attack" type="submit">Atacar</button>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card player-card">
        <h5>Jugadores</h5>
        <div id="playerList" class="row"></div>
      </div>
    </div>
  </div>
</div>

<script>
// 🔹 Enviar ataque por AJAX
$("#formAtaque").on("submit", function(e){
  e.preventDefault();
  $.post("registrar_ataque.php", $(this).serialize(), function(resp){
    if(resp.trim() === "FINALIZADA"){
      window.location.href = "finalizar_partida.php?id_partida=<?= $id_partida ?>";
    } else {
      refreshStatus();
    }
  });
});
</script>
</body>
</html>
