<?php
session_start();
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

$username = $_SESSION['username'];

// Obtener datos del usuario
$sqlusuario = $con->prepare("SELECT u.puntos_actuales, u.id_nivel, n.nombre AS nombre_nivel, n.puntos_requeridos, n.imagen_url, id_personaje
FROM usuario u INNER JOIN niveles n ON u.id_nivel = n.id_nivel WHERE u.username = :username");
$sqlusuario->bindParam(':username', $username);
$sqlusuario->execute();
$usuario = $sqlusuario->fetch(PDO::FETCH_ASSOC);

// Obtener avatar actual
$sqlavatar  = $con->prepare("SELECT * FROM avatar WHERE id_avatar = (SELECT id_avatar FROM usuario WHERE username = :username)");
$sqlavatar->bindParam(':username', $username);
$sqlavatar->execute();
$avatar = $sqlavatar->fetch(PDO::FETCH_ASSOC);

// Obtener imagen del personaje seleccionado
$imagen_personaje = null;
if (!empty($usuario['id_personaje'])) {
    $sqlpersonaje = $con->prepare("SELECT personaje_foto FROM personaje WHERE id_personaje = :id");
    $sqlpersonaje->bindParam(':id', $usuario['id_personaje']);
    $sqlpersonaje->execute();
    $personaje = $sqlpersonaje->fetch(PDO::FETCH_ASSOC);
    $imagen_personaje = $personaje['personaje_foto'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lobby Mortal Kombat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      background: url("img/lobby.png") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Arial Black', sans-serif;
      height: 100vh;
      overflow: hidden;
    }

    .menu-container {
      height: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 40px;
      position: relative;
    }

    .btn-ff {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 10px;
      width: 220px;
      padding: 12px 18px;
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      color: #fff;
      border: none;
      border-radius: 6px;
      background: linear-gradient(90deg, #2b2b2b56, #1c1c1c44);
      box-shadow: 0 4px 8px rgba(0,0,0,0.6);
      transition: all 0.2s ease;
    }

    .btn-ff:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #8b030363, #a00);
    }

    .btn-ff-yellow {
      background: linear-gradient(90deg, #ff0000, #a00);
      color: #000;
    }

    .btn-ff-yellow:hover {
      background: linear-gradient(90deg, #a00, #550000);
      color: #fff;
    }

    .character {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.character img {
  max-height: 420px;
  display: block;
  margin: 0 auto;
}


    .menu-left, .menu-right {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .profile-card {
      position: absolute;
      top: 40px;
      right: 40px;
      width: 380px;      
      padding: 30px;
      background: rgba(20, 20, 20, 0.15);
      border-radius: 20px;
      backdrop-filter: blur(8px);
      animation: profile-glow 2s infinite alternate;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 15px;
      box-shadow: 0 0 20px rgba(255,0,0,0.4);
      border: 2px solid rgba(255, 0, 0, 0.3);
    }

    .profile-card .profile-avatar img {
      width: 120px; 
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ff0000;
      box-shadow: 0 0 20px rgba(255, 0, 0, 0.7);
      margin-bottom: 10px;
    }

    .profile-card .profile-name {
      font-size: 26px;
      color: #fff;
      margin: 0;
      text-shadow: 0 0 10px #ff0000;
    }

    .profile-card .profile-level {
      font-size: 18px;
      color: #ddd;
      margin: -5px 0 5px 0;
    }

    .profile-card .profile-progress {
      width: 95%;
      height: 20px;
      border-radius: 10px;
    }

    .profile-card .btn-ff-yellow {
      margin-top: 15px;
      width: 100%;
      font-size: 20px;
      padding: 15px;
      text-align: center;
      justify-content: center;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(255,0,0,0.5);
    }

    @keyframes profile-glow {
      0% {
        box-shadow: 0 0 10px #a00, 0 0 20px #ff3300, 0 0 40px #ff0000;
      }
      100% {
        box-shadow: 0 0 25px #ff0000, 0 0 30px #ff6600, 0 0 40px #ffcc00;
      }
    }

    .avatar-menu-izquierda {
      position: absolute;
      top: 40px;
      left: 40px;
      border-radius: 50%;
      border: 3px solid #a00;
      object-fit: contain;
      box-shadow: 0 0 8px rgba(255, 0, 0, 0.7);
    }
  </style>
</head>
<body>

  <div class="menu-container">
    <div class="menu-left">
      <a href="" onclick="window.open ('paginas/avatar.php', '', 'width=500, height=500, toolbar=no'); void(null);">
        <img src="<?php echo $avatar['avatar_foto']?>" alt="avatar" class="avatar-menu-izquierda" style="height:80px; vertical-align: middle; margin-right: 10px;">
      </a>
      <button class="btn-ff" onclick="window.location.href='paginas/personajes.php'"><i class="fas fa-users"></i> Personajes</button>
      <button class="btn-ff" onclick="window.location.href='paginas/estadisticas.php'"><i class="fas fa-chart-bar"></i> Estadísticas</button>
      <button class="btn-ff" onclick="window.location.href='paginas/armas.php'"> <i class="fas fa-gun"></i> Armas</button>
    </div>

    <div class="character">
      <?php if (!empty($imagen_personaje)): ?>
        <img src="<?php echo $imagen_personaje; ?>" alt="personaje-seleccionado">
      <?php endif; ?>
    </div>

    <div class="menu-right">
      <div class="profile-card">
        <div class="profile-avatar"> 
          <img src="<?php echo $usuario['imagen_url']?>" alt="avatar">
        </div>
        <h4 class="profile-name"><?php echo $username;?></h4>
        <p class="profile-level">Nivel: <?php echo $usuario['nombre_nivel']?></p>
        <meter class="profile-progress" id="barraProgreso" value="<?php echo $usuario['puntos_actuales']?>" low="33" high="66" optimum="100"></meter>
        <p class="profile-level">Puntos actuales: <?php echo $usuario['puntos_actuales']?></p>

        <button class="btn-ff-yellow" onclick="window.location.href='paginas/mapas.php'"><i class="fas fa-play"></i> Iniciar</button>
      </div>
    </div>
  </div>

  <script>
    window.addEventListener('message', function(event) {
      if (event.data.tipo === 'avatar_actualizado') {
        const nuevoAvatar = event.data.avatar;
        const avatarImg = document.querySelector('.avatar-menu-izquierda');
        if (avatarImg) {
          avatarImg.src = nuevoAvatar;
        }
      }
    });
  </script>
  <script>
    function actualizarbarra(puntos, nivel){
      const barra = document.getElementById("barraProgreso");

      switch(nivel){
        case 1:
          barra.max=500;
          barra.min=0;
          break
        case 2:
          barra.max=750;
          barra.min=501;
          break;
        case 3:
          barra.max=1000;
          barra.min=751;
          break;
        case 4:
          barra.max=1250;
          barra.min=1001;
          break;
          case 5:
          barra.max=1500;
          barra.min=1251;
          break;
          default:
            barra.min = 0;
            barra.max = 100;
      }
    }
  actualizarbarra(<?php echo $usuario['puntos_actuales']; ?>, <?php echo $usuario['id_nivel']; ?>);

  </script>
</body>
</html>
