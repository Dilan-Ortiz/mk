<?php
session_start();
require_once 'config/database.php';
require_once 'includes/global.php';
$db = new Database();
$con = $db->conectar();

//Verificar sesión
if (!isset($_SESSION['documento']) || empty($_SESSION['documento'])) {
    die("No hay documento en sesión. Inicia sesión nuevamente.");
}
$documento = $_SESSION['documento'];

//Verificar parámetro sala
if (!isset($_GET['sala'])) {
    die("No se seleccionó ninguna sala.");
}
$id_sala = intval($_GET['sala']);

//Buscar la sala (permitir 'abierta' o 'en_partida')
$stmt = $con->prepare("SELECT * FROM salas WHERE id_sala = :id_sala AND estado IN ('abierta', 'en_partida')");
$stmt->execute([':id_sala' => $id_sala]);
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sala) {
    die("La sala no existe o está cerrada.");
}

//Obtener id_nivel del usuario
$stmt = $con->prepare("SELECT id_nivel FROM usuario WHERE documento = :documento");
$stmt->execute([':documento' => $documento]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuario no encontrado.");
}

$id_nivel_usuario = intval($usuario['id_nivel']);

//Si la sala aún no tiene nivel, asignarle el del primer usuario
if (empty($sala['id_nivel'])) {
    $stmt = $con->prepare("UPDATE salas SET id_nivel = :id_nivel WHERE id_sala = :id_sala");
    $stmt->execute([
        ':id_nivel' => $id_nivel_usuario,
        ':id_sala' => $id_sala
    ]);
    $sala['id_nivel'] = $id_nivel_usuario;
}

//Validar nivel del usuario vs sala
if ($id_nivel_usuario !== intval($sala['id_nivel'])) {
    die("No puedes unirte. Esta sala es solo para usuarios del nivel " . $sala['id_nivel']);
}

//Contar jugadores actuales
$stmt = $con->prepare("SELECT COUNT(*) AS total FROM sala_usuarios WHERE id_sala = :id_sala");
$stmt->execute([':id_sala' => $id_sala]);
$total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

//Validar cupos
if ($total >= (int)$sala['max_jugadores']) {
    die("La sala está llena.");
}

//Verificar si el usuario ya está en la sala
$stmt = $con->prepare("SELECT id_sala_usuario FROM sala_usuarios WHERE documento = :documento AND id_sala = :id_sala");
$stmt->execute([
    ':documento' => $documento,
    ':id_sala' => $id_sala
]);
$existe = $stmt->fetch(PDO::FETCH_ASSOC);

//Si no está, agregarlo
if (!$existe) {
    $stmt = $con->prepare("INSERT INTO sala_usuarios (id_sala, documento) VALUES (:id_sala, :documento)");
    $stmt->execute([
        ':id_sala' => $id_sala,
        ':documento' => $documento
    ]);

    $stmt = $con->prepare("UPDATE salas SET jugadores_actuales = jugadores_actuales + 1 WHERE id_sala = :id_sala");
    $stmt->execute([':id_sala' => $id_sala]);
}

//Redirigir al lobby
header("Location: lobby_partida.php?sala=" . $id_sala);
exit;
?>
