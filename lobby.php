<?php
session_start();
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

$username = $_SESSION['username'];
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
      background: url("img/lobby2.png") no-repeat center center fixed;
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


    .btn-ff img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
    
    .btn-ff-yellow {
      background: linear-gradient(90deg, #ffb703, #fb8500);
      color: #000;
    }
    .btn-ff-yellow:hover {
      background: linear-gradient(90deg, #ff9e00, #d97706);
      color: #fff;
    }
    .character {
      text-align: center;
    }
    .character img {
      max-height: 420px;
    }

    .menu-left, .menu-right {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

.btn-iniciar {
  position: absolute;
  bottom: 30px;
  right: 30px;
  width: 300px;
  padding: 18px 25px;
  font-size: 22px;
  text-align: center;
  justify-content: center;
  border-radius: 8px;
}

.progress-top {
  position: absolute;
      top: 20px;
      left: 20px;
      color: #dbf306ff;
      font-weight: bold;
      font-size: 18px;
      padding: 10px 20px;
      background: #1a1a1a15;
      border-radius: 10px;
      animation: progress-top 2s infinite alternate;
      display: flex;
      align-items: center;
      gap: 10px;
}

.progress-top meter {
  width: 300px;
  height: 20px;
}
  @keyframes progress-top {
      0% {
        box-shadow: 0 0 10px #a00, 0 0 10px #ff3300, 0 0 20px #ff0000;
      }
      100% {
        box-shadow: 0 0 25px #ffcc00, 0 0 10px #ff6600, 0 0 20px #a00;
      }
    }
  </style>
</head>
<body>

  <div class="menu-container">
    <div class="progress-top">
      <h4> <?php echo $username;?></h4>
      <a href="" onclick="window.open ('avatar.php', '', 'width=500, height=500, toolbar=no'); void(null);" class="btn">
        <img src="uploads/stryker.png" alt="avatar" style="height:40px; vertical-align: middle; margin-right: 10px;"></a>
  <label>Progreso:</label>
  <meter value="80" min="0" max="100" low="33" high="66" optimum="100"></meter>
</div>
    <div class="menu-left">
      <div class="btn-ff"> <img src="img/armas/kunai.png" alt="avatar"></div>
      <button class="btn-ff" onclick="window.location.href='paginas/personajes.php'"><i class="fas fa-users"></i> Personajes</button>
      <button class="btn-ff" onclick="window.location.href='paginas/estadisticas.php'"><i class="fas fa-chart-bar"></i> Estadísticas</button>
      <button class="btn-ff" onclick="window.location.href='paginas/armas.php'"> <i class="fas fa-gun"></i> Armas</button>

    </div>

    <div class="character">
      <img src="personajes/hero1.webp" alt="personaje">
    </div>

    <div class="menu-right">
      
      <div class="btn-ff"> <img src="img/mapa1.png" alt=""></div>
    </div>
        <button class="btn-ff-yellow btn-iniciar" onclick="window.location.href='/mk/paginas/mapas.php'"><i class="fas fa-play"></i> Iniciar</button>
  </div>

</body>
</html>
