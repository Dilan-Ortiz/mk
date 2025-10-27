<?php
session_start();
require_once '../config/database.php';

$db = new Database();
$con = $db->conectar();
$sql = $con->prepare("SELECT * FROM avatar");
$sql->execute();
$resultados12 = $sql->fetchAll(PDO::FETCH_ASSOC);


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['id_avatar'])){
        $id_avatar_seleccionado = $_POST['id_avatar'];
        $_SESSION['id_avatar'] = $id_avatar_seleccionado;
        $doc = $_SESSION['documento'];

            $updateSQL = $con->prepare("UPDATE usuario SET id_avatar = '$id_avatar_seleccionado' WHERE documento = '$doc'");
            $exitosa = $updateSQL->execute();
            if ($exitosa) {
                echo '<script>alert("Avatar seleccionado correctamente");</script>';
                echo '<script>window.close()</script>';
                exit;
            } else {
                echo '<script>alert("Error: No se pudo actualizar el avatar");</script>';
                exit;
            }
    } else {
        echo '<script>alert("No se ha seleccionado ningún avatar.");</script>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Selección de Personajes - Mortal Kombat</title>

<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap" rel="stylesheet">

<style>
  body {
    margin: 0;
    background: url('img/plano.jpg') no-repeat center center/cover;
    color: white;
    font-family: 'Cinzel Decorative', cursive;
    text-align: center;
  }

  h1 {
    margin-top: 30px;
    font-size: 3em;
    text-shadow: 3px 3px 12px red;
    letter-spacing: 2px;
  }

  .contenedor {
    margin-top: 40px;
    margin-left: 0px; 
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(5, 160px); 
    gap: 25px;
    justify-content: center;
  }

  .personaje {
    width: 150px;
    height: 150px;
    border: 3px solid transparent;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: 0.3s;
    background-color: rgba(0, 0, 0, 0.4);
  }

  .personaje img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: brightness(0.8);
    transition: 0.3s;
  }

  .personaje:hover img {
    filter: brightness(1);
    transform: scale(1.1);
  }

  .seleccionado {
    border-color: gold;
    box-shadow: 0 0 20px gold;
  }

  #seleccionFinal {
    margin-top: 30px;
    font-size: 1.8em;
    text-shadow: 2px 2px 10px black;
  }

  button {
    background: red;
    color: white;
    border: none;
    padding: 12px 25px;
    margin-top: 25px;
    font-size: 1.3em;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
    font-family: 'Cinzel Decorative', cursive;
  }

  button:hover {
    background: darkred;
    transform: scale(1.05);
  }
</style>
</head>
<body>

  <h1>Elige tu avatar </h1>


    <form id="form_avatar">
  <div class="grid" style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
    <?php
    $control = $con->prepare("SELECT * FROM avatar");
    $control->execute();
    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
        echo '
        <label style="cursor:pointer;">
            <input type="radio" name="id_avatar" value="' . $fila['id_avatar'] . '" style="display:none;">
            <img src="../' . $fila['avatar_foto'] . '" 
            alt="Avatar" 
            style="width:70px; height:70px; object-fit:cover; border-radius:50%; border:2px solid transparent; transition:0.3s;">
        </label>';
    }
    ?>
  </div>
  <div style="text-align:center; margin-top:20px;">
    <button type="submit" id="confirmar_btn" style="padding:10px 30px; background-color:#a00; color:white; border:none; border-radius:5px; cursor:pointer; font-size:1.2em;">
      Confirmar Avatar
    </button>
  </div>
</form>

<script>
document.getElementById('form_avatar').addEventListener('submit', function(e) {
  e.preventDefault();
  const id_avatar = document.querySelector('input[name="id_avatar"]:checked');
  if (!id_avatar) {
    alert('Selecciona un avatar');
    return;
  }

  fetch('actualizar_avatar.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id_avatar=' + encodeURIComponent(id_avatar.value)
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      alert('Avatar actualizado correctamente');
      if (window.opener) {
        window.opener.postMessage({ tipo: 'avatar_actualizado', avatar: data.avatar }, '*');
      }
      window.close();
    } else {
      alert('Error al actualizar: ' + data.message);
    }
  })
  .catch(error => console.error('Error:', error));
});
</script>

</body>
</html>