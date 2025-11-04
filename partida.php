<?php
session_start();

try {
    $conexion = new PDO("mysql:host=localhost;dbname=mk;charset=utf8", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$mi_documento = $_SESSION['documento'];

// Obtener nivel del usuario
$sql = $conexion->prepare("SELECT id_nivel FROM usuario WHERE documento = ?");
$sql->execute([$mi_documento]);
$nivel = $sql->fetchColumn();

if (!$nivel) die("Nivel no encontrado.");

// Obtener armas disponibles según nivel
$sql = $conexion->prepare("SELECT id_arma, nombre, daño, tipo FROM armas WHERE id_nivel <= ?");
$sql->execute([$nivel]);
$armas_res = $sql->fetchAll(PDO::FETCH_ASSOC);

// Validar partida
$id_partida = intval($_GET['id_partida'] ?? 0);
if ($id_partida <= 0) die("Partida no especificada.");

// Obtener información de la partida
$sql = $conexion->prepare("SELECT * FROM partidas WHERE id_partida = ? LIMIT 1");
$sql->execute([$id_partida]);
$partida = $sql->fetch(PDO::FETCH_ASSOC);
if (!$partida) die("Partida no encontrada.");

// Validar que el usuario pertenezca a la partida
$sql = $conexion->prepare("SELECT documento FROM usuario_partida WHERE id_partida = ?");
$sql->execute([$id_partida]);
$documentos = $sql->fetchAll(PDO::FETCH_COLUMN);
if (!in_array($mi_documento, $documentos)) die("No perteneces a esta partida.");

// Calcular tiempo restante (5 min = 300 seg)
$inicio = strtotime($partida['inicio']);
$ahora = time();
$tiempo_pasado = $ahora - $inicio;
$tiempo_restante = max(0, 300 - $tiempo_pasado);

// Armas del usuario
$sql = $conexion->prepare("
    SELECT a.id_arma, a.nombre, a.daño, a.tipo 
    FROM usuario_armas ua 
    JOIN armas a ON ua.id_arma = a.id_arma 
    WHERE ua.documento = ?
");
$sql->execute([$mi_documento]);
$armas_usuario = $sql->fetchAll(PDO::FETCH_ASSOC);

// Jugadores de la partida
$sql = $conexion->prepare("
    SELECT up.documento, u.username, u.id_avatar, up.vida_restante, up.puntos_acumulados, up.eliminado, a.avatar_foto
    FROM usuario_partida up
    JOIN usuario u ON up.documento = u.documento
    LEFT JOIN avatar a ON u.id_avatar = a.id_avatar
    WHERE up.id_partida = ?
    ORDER BY up.puntos_acumulados DESC
");
$sql->execute([$id_partida]);
$jugadores_res = $sql->fetchAll(PDO::FETCH_ASSOC);
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
#timer{font-weight:700;color:#ffdddd;}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function refreshStatus(){
  $("#status").load("partida_estado_ajax.php?id_partida=<?= $id_partida ?>");
}
setInterval(refreshStatus, 2000);

let tiempo = <?= $tiempo_restante ?>;
setInterval(()=> {
  if (tiempo>0) tiempo--;
  document.getElementById('timer').innerText = tiempo + "s";
  if (tiempo === 0) {
    window.location.href = "finalizar_partida.php?id_partida=<?= $id_partida ?>";
  }
}, 1000);
</script>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3>Partida #<?= $id_partida ?> — Mapa: <?= htmlspecialchars($partida['id_sala']) ?></h3>
      <div class="small">Estado: <?= htmlspecialchars($partida['estado']) ?></div>
    </div>
    <div><div id="timer"><?= $tiempo_restante ?>s</div></div>
  </div>

  <div id="status" class="mb-4"></div>

  <div class="row">
    <div class="col-md-6">
      <div class="card player-card">
        <h5>Tu arma y objetivo</h5>
        <form action="registrar_ataque.php" method="POST">
          <input type="hidden" name="id_partida" value="<?= $id_partida ?>">

          <div class="mb-2">
            <label class="small">Selecciona arma</label>
            <select name="id_arma" class="form-select" required>
              <?php foreach ($armas_res as $a): ?>
                <option value="<?= $a['id_arma'] ?>"><?= htmlspecialchars($a['nombre']) ?> (<?= $a['daño'] ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-2">
            <label class="small">Atacar a</label>
            <select name="objetivo" class="form-select" required>
              <?php foreach ($jugadores_res as $p): ?>
                <?php if ($p['documento'] == $mi_documento || $p['eliminado']) continue; ?>
                <option value="<?= $p['documento'] ?>"><?= htmlspecialchars($p['username']) ?> — Vida: <?= max(0, $p['vida_restante']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-2">
            <label class="small">Parte del cuerpo</label>
            <select name="parte" class="form-select" required>
              <option value="cabeza">Cabeza (x2)</option>
              <option value="torso">Torso (x1)</option>
              <option value="piernas">Piernas (x0.5)</option>
            </select>
          </div>

          <button class="btn-attack" type="submit">Atacar</button>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card player-card">
        <h5>Jugadores (vida / puntos)</h5>
        <div class="row">
          <?php foreach ($jugadores_res as $p): ?>
          <div class="col-6 mb-3">
            <div class="d-flex gap-2 align-items-center">
              <img src="../<?= htmlspecialchars($p['avatar_foto']) ?>" class="avatar">
              <div>
                <b><?= htmlspecialchars($p['username']) ?></b>
                <div class="small">Vida: <?= max(0, $p['vida_restante']) ?>/1000</div>
                <div class="health"><div style="width:<?= max(0,min(100, ($p['vida_restante']/10))) ?>%"></div></div>
                <div class="small">Puntos: <?= intval($p['puntos_acumulados']) ?></div>
                <div class="small">Eliminado: <?= $p['eliminado'] ? 'Sí':'No' ?></div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
