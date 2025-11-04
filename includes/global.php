<?php
// Inicia la sesión para poder usar variables globales ($_SESSION)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluye el archivo de conexión a la base de datos
require_once("config/database.php");
$db = new Database();
$con = $db->conectar();

// consulta de nivel del usuario
$sqlNivel = $con->prepare("SELECT n.id_nivel, n.nombre, n.puntos_requeridos, n.imagen_url
    FROM niveles n INNER JOIN usuario u ON u.id_nivel = n.id_nivel WHERE u.documento = ?");

// Ejecuta la consulta usando el documento del usuario almacenado en sesión
$sqlNivel->execute([$_SESSION['documento']]);

// Obtiene los datos como un arreglo asociativo
$nivel = $sqlNivel->fetch(PDO::FETCH_ASSOC);

// Si se encontró un nivel, guarda los datos en variables globales de sesión
if ($nivel) {
    $_SESSION['id_nivel'] = $nivel['id_nivel'];
    $_SESSION['nombre_nivel'] = $nivel['nombre'];
    $_SESSION['puntos_requeridos'] = $nivel['puntos_requeridos'];
    $_SESSION['imagen_nivel'] = $nivel['imagen_url'];
}

// consulta de sala en la que está el usuario
$sqlSala = $con->prepare("SELECT s.id_sala, s.nombre_sala, s.id_mundo FROM salas s
    INNER JOIN sala_usuarios su ON su.id_sala = s.id_sala
    WHERE su.documento = ? ORDER BY s.id_sala DESC");

$sqlSala->execute([$_SESSION['documento']]);
$sala = $sqlSala->fetch(PDO::FETCH_ASSOC);

if ($sala) {
    $_SESSION['id_sala'] = $sala['id_sala'];
    $_SESSION['nombre_sala'] = $sala['nombre_sala'];
    $_SESSION['id_mundo'] = $sala['id_mundo'];
}

// consulta de avatar asignado al usuario
$sqlavatares = $con->prepare("SELECT a.id_avatar, a.avatar_foto FROM avatar a
    INNER JOIN usuario u ON u.id_avatar = a.id_avatar WHERE u.documento = ? ");

$sqlavatares->execute([$_SESSION['documento']]);
$avatar = $sqlavatares->fetch(PDO::FETCH_ASSOC);

// Si el usuario tiene avatar, se guardan los datos en la sesión
if ($avatar) {
    $_SESSION['id_avatar'] = $avatar['id_avatar'];
    $_SESSION['avatar_foto'] = $avatar['avatar_foto'];
}

// consulta de personaje asignado al usuario
$sqlpersonaje = $con->prepare("SELECT p.id_personaje, p.nombre, p.id_nivel, p.personaje_foto
    FROM personaje p INNER JOIN usuario u ON u.id_personaje = p.id_personaje
    WHERE u.documento = ?");

$sqlpersonaje->execute([$_SESSION['documento']]);
$personaje = $sqlpersonaje->fetch(PDO::FETCH_ASSOC);

// Si el usuario tiene personaje asignado, guarda los datos
if ($personaje) {
    $_SESSION['id_personaje'] = $personaje['id_personaje'];
    $_SESSION['nombre_personaje'] = $personaje['nombre'];
    $_SESSION['id_nivel_personaje'] = $personaje['id_nivel'];
    $_SESSION['personaje_foto'] = $personaje['personaje_foto'];
}


// consulta de arma asignada al usuario
$sqlArma = $con->prepare("SELECT a.id_arma, a.nombre, a.daño, a.imagen_url
    FROM usuario_armas ua INNER JOIN armas a ON ua.id_arma = a.id_arma
    WHERE ua.documento = ? ORDER BY ua.id_arma DESC LIMIT 1");

$sqlArma->execute([$_SESSION['documento']]);
$arma = $sqlArma->fetch(PDO::FETCH_ASSOC);

// Si se encuentra un arma asignada al usuario, se guarda en la sesión
if ($arma) {
    $_SESSION['id_arma'] = $arma['id_arma'];
    $_SESSION['nombre'] = $arma['nombre'];
    $_SESSION['daño_arma'] = $arma['daño'];
    $_SESSION['imagen_arma'] = $arma['imagen_url'];
}

?>
