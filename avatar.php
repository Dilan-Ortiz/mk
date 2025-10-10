<?php
session_start();
require_once 'config/database.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT * FROM avatar");
$sql->execute();
$resultados12 = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="avatar-container" style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
    <?php
    $control = $con->prepare("SELECT * FROM avatar");
    $control->execute();
    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
        echo '
        <label style="cursor:pointer;">
            <input type="radio" name="id_avatar" value="' . $fila['id_avatar'] . '" style="display:none;">
            <img src="' . $fila['avatar_foto'] . '" 
            alt="Avatar" 
            style="width:70px; height:70px; object-fit:cover; border-radius:50%; border:2px solid transparent; transition:0.3s;">
        </label>';
    }
    ?>
</div>
</body>
</html>


