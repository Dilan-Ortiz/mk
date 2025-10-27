<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}
$documento = $_SESSION['documento'];

if (!isset($_GET['sala'])) {
    die("No se seleccionó ninguna sala.");
}
$id_sala = intval($_GET['sala']);


$sql_sala = mysqli_query($conexion, "SELECT * FROM salas WHERE id_sala = $id_sala");
if (mysqli_num_rows($sql_sala) == 0) {
    die("La sala no existe.");
}
$sala = mysqli_fetch_assoc($sql_sala);


$check = mysqli_query($conexion, "SELECT * FROM sala_usuarios WHERE documento='$documento' AND id_sala=$id_sala");
if (mysqli_num_rows($check) == 0) {
    die("No perteneces a esta sala.");
}


$query_jugadores = "
SELECT u.username, a.avatar_foto
FROM sala_usuarios su
JOIN usuario u ON su.documento = u.documento
JOIN avatar a ON u.id_avatar = a.id_avatar
WHERE su.id_sala = $id_sala
";
$resultado_jugadores = mysqli_query($conexion, $query_jugadores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Lobby - <?= htmlspecialchars($sala['nombre_sala']) ?></title>
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


.lobby-container h1 {
  color: #ff0000ff;
  font-size: 2.5rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 10px;
  text-shadow: 0 0 20px #ff000dff;
}

.lobby-container p {
  color: #ddd;
  font-size: 1rem;
  margin-bottom: 40px;
}


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


.btn-iniciar {
  background: linear-gradient(90deg, #ff004cff, #ff0077ff);
  border: none;
  font-size: 1.2rem;
  padding: 14px 40px;
  border-radius: 10px;
  margin-top: 45px;
  color: white;
  cursor: pointer;
  transition: 0.3s;
  box-shadow: 0 0 20px rgba(255, 0, 0, 0.4);
}

.btn-iniciar:hover {
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
setInterval(() => {
    $("#jugadores").load("lobby_jugadores.php?sala=<?= $id_sala ?>");
}, 3000);
</script>
</head>
<body>

<div class="lobby-container">
    <h1 class="text-danger mb-3"><?= htmlspecialchars($sala['nombre_sala']) ?></h1>
    <p>Listos para la guerra... que gane el mejor</p>

    <div id="jugadores" class="avatar-grid">
        <?php while ($jugador = mysqli_fetch_assoc($resultado_jugadores)): ?>
            <div class="avatar-card">
                <img src="../<?= htmlspecialchars($jugador['avatar_foto']) ?>" alt="Avatar">
                <span><?= htmlspecialchars($jugador['username']) ?></span>
            </div>
        <?php endwhile; ?>
    </div>

    
    <form action="iniciar_partida.php" method="POST">
    <input type="hidden" name="id_sala" value="<?= $id_sala ?>">
    <button class="btn-iniciar">Iniciar Partida</button>
</form>

 
    <form action="salir_sala.php" method="POST">
        <input type="hidden" name="id_sala" value="<?= $id_sala ?>">
        <input type="hidden" name="id_mundo" value="<?= $sala['id_mundo'] ?>">
        <button class="btn-salir"> Abandonar partida</button>
    </form>
</div>

</body>
</html>