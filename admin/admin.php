<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Primera vista de administrador</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bulma -->
  <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
      background: url("../img/administrador.jpg") no-repeat center center fixed;
      background-size: cover;
      font-family: Arial, sans-serif;
      color: white;
    }

    .navbar {
      height: 180px;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      position: fixed;
      top: 0;
      z-index: 1000;
      width: 100%;
    }

    .navbar h1 {
      font-family: 'Georgia', serif;
      color: #fff;
      text-shadow: 5px 5px 5px #000;
      font-size: 2.5rem;
      text-align: center;
      margin: 0 auto;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 30px;
      background-color: #e63946;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      border-radius: 30px;
      color: white;
      text-shadow: 1px 1px 2px #000;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .logout-btn:hover {
      background-color: #d62828;
      transform: scale(1.05);
    }

    .card-container {
      display: flex;
      justify-content: space-around;
      align-items: center;
      height: 80vh;
      margin-top: 100px;
      padding: 0 100px;
    }

    .card {
      width: 300px;
      height: 300px;
      border-radius: 15px;
      overflow: hidden;
      background: rgba(0, 0, 0, 0.8); 
      border: 2px solid rgba(255, 255, 255, 0.2); 
      box-shadow: 0 0 20px rgba(255, 0, 0, 0.3); 
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      color: #fff;
    }

    .card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .card p {
      margin: 10px 0;
      font-weight: bold;
      font-size: 1rem;
      text-shadow: 1px 1px 2px #000;
    }

    .card:hover {
      transform: scale(1.07);
      box-shadow: 0 0 30px rgba(255, 0, 0, 0.6);
    }
  </style>
</head>
<body>
  <div class="navbar">
    <h1>Bienvenido, Administrador</h1>
    <a href="cerrar.php" class="btn btn-danger logout-btn">Cerrar sesión</a>
  </div>

  <div class="card-container">
    <div class="card" onclick="location.href='usuario_admi.php'">
      <img src="../img/logo.png" alt="Consulta jugadores">
      <p>Consulta de jugadores</p>
    </div>

    <div class="card" onclick="location.href='estadisticas.php'">
      <img src="../img/usuarios.jpg" alt="Consulta estadísticas">
      <p>Consulta de estadísticas</p>
    </div>
  </div>
</body>
</html>
