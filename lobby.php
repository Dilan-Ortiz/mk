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
      background: linear-gradient(90deg, #2b2b2b, #1c1c1c);
      box-shadow: 0 4px 8px rgba(0,0,0,0.6);
      transition: all 0.2s ease;
    }
    .btn-ff:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #ffb703, #fb8500);
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

  </style>
</head>
<body>

  <div class="menu-container">
    <div class="menu-left">
      <button class="btn-ff" onclick="window.location.href='paginas/personajes.php'"><i class="fas fa-users"></i> Personajes</button>
      <button class="btn-ff" onclick="window.location.href='paginas/estadisticas.php'"><i class="fas fa-chart-bar"></i> Estadísticas</button>
      <button class="btn-ff" onclick="window.location.href='paginas/armas.php'"> <i class="fas fa-gun"></i> Armas</button>

    </div>

    <div class="character">
      <img src="personajes/hero1.webp" alt="personaje">
    </div>

    <div class="menu-right">
      <button class="btn-ff" onclick="window.location.href='paginas/mapas.php'"><i class="fas fa-map"></i> Mapas</button>
    </div>

    <button class="btn-ff-yellow btn-iniciar"><i class="fas fa-play"></i> Iniciar</button>
  </div>

</body>
</html>
