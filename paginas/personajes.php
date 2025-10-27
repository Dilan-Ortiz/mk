<?php
session_start();
require_once '../config/database.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT * FROM personaje");
$sql->execute();
$resultados12 = $sql->fetchAll(PDO::FETCH_ASSOC);

$documento_usuario = $_SESSION['documento'];
$sql_usuario = $con->prepare("SELECT id_nivel FROM usuario WHERE documento = :doc");
$sql_usuario->bindParam(':doc', $documento_usuario);
$sql_usuario->execute();
$usuario = $sql_usuario->fetch(PDO::FETCH_ASSOC);
$nivel_usuario_actual = $usuario['id_nivel'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Selecciona tu personaje</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(#291111, #000);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Trebuchet MS';
    }
    .weapon-container {
      background: rgba(30, 30, 30, 0.9);
      border: 4px solid #a00;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0px 0px 25px rgba(255, 0, 0, 0.6);
    }
    .title { color: #ff0000; text-align: center; margin-bottom: 25px; }
    .weapon-grid {
      display: grid;
      grid-template-columns: repeat(4, 170px);
      gap: 20px;
      justify-content: center;
    }
    .card-dark {
      background: linear-gradient(180deg, #2b2b2b, #1a1a1a);
      border: 2px solid #660000;
      border-radius: 8px;
      text-align: center;
      color: #ddd;
      transition: all 0.25s ease-in-out;
      position: relative;
    }
    .card-dark:hover {
      transform: scale(1.1);
      border-color: #ff0000;
    }
    .card-dark img {
      width: 100%;
      height: 100px;
      object-fit: contain;
      border-bottom: 2px solid #550000;
    }
    .capa-bloqueo {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.85);
      display: flex; flex-direction: column;
      justify-content: center; align-items: center;
      color: #ff4444;
      border-radius: 8px;
    }
    .btn-ff {
      margin-top: 10px;
      padding: 8px 12px;
      background: linear-gradient(90deg, #2b2b2b, #1c1c1c);
      color: white; border: none; border-radius: 6px;
      cursor: pointer;
    }
    .btn-ff:hover {
      background: linear-gradient(90deg, #790d0dff, #2c080bff);
      box-shadow: 0 0 10px #ff0000;
    }
  </style>
</head>
<body>
  <div class="weapon-container" id="contenedor-personajes">
    <h1 class="title">Selecciona tu personaje</h1>
    <div class="weapon-grid" id="grid-personajes">
      <?php foreach ($resultados12 as $resultado): 
        $nivel_requerido = $resultado['id_nivel'];
        $esta_bloqueada = $nivel_usuario_actual < $nivel_requerido;
      ?>
        <div class="card-dark <?php echo $esta_bloqueada ? 'bloqueada' : ''; ?>" id="personaje-<?php echo $resultado['id_personaje']; ?>">
          <img src="../<?php echo $resultado['personaje_foto']; ?>" alt="<?php echo $resultado['nombre']; ?>" class="img-personaje">
          <div class="card-body">
            <p class="dano-personaje">nombre <?php echo $resultado['nombre']; ?></p>
            <?php if (!$esta_bloqueada): ?>
              <form id="form-personaje-<?php echo $resultado['id_personaje']; ?>" class="form-personaje" method="POST" action="guardar_personaje.php">
                <input type="hidden" id="id_personaje_<?php echo $resultado['id_personaje']; ?>" name="id_personaje" value="<?php echo $resultado['id_personaje']; ?>">
                <button type="submit" id="btn-seleccionar-<?php echo $resultado['id_personaje']; ?>" class="btn-ff btn-seleccionar">Seleccionar</button>
              </form>
            <?php else: ?>
              <div class="capa-bloqueo">
                <p>🔒 Nivel <?php echo $nivel_requerido; ?> requerido</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <button id="btn-volver" class="btn-ff" onclick="window.location.href='../lobby.php'">Volver</button>
  </div>
</body>
</html>
