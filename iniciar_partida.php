<?php
session_start();
require_once("config/database.php");

$db = new Database();
$con = $db->conectar();

// Verificar recepción del id_sala
if (isset($_POST['id_sala'])) {
    $id_sala = intval($_POST['id_sala']);
} elseif (isset($_GET['id_sala'])) {
    $id_sala = intval($_GET['id_sala']);
} else {
    die("No se recibió el ID de la sala.");
}

// Verificar si la sala existe
$sqlSala = $con->prepare("SELECT * FROM salas WHERE id_sala = ?");
$sqlSala->execute([$id_sala]);
$sala_datos = $sqlSala->fetch(PDO::FETCH_ASSOC);

if (!$sala_datos) {
    die("La sala no existe.");
}

// Obtener los jugadores de la sala
$sqlJugadores = $con->prepare("SELECT documento FROM sala_usuarios WHERE id_sala = ?");
$sqlJugadores->execute([$id_sala]);
$jugadores = $sqlJugadores->fetchAll(PDO::FETCH_ASSOC);
$cantidad = count($jugadores);

if ($cantidad < 2) {
    die("No se puede iniciar la partida con menos de 2 jugadores.");
}

// Insertar nueva partida
$sqlInsertPartida = $con->prepare("INSERT INTO partidas (id_sala, estado, inicio) VALUES (?, 'en_curso', NOW())");
$sqlInsertPartida->execute([$id_sala]);
$id_partida = $con->lastInsertId();

// Actualizar estado de la sala
$sqlUpdateSala = $con->prepare("UPDATE salas SET estado = 'en_partida' WHERE id_sala = ?");
$sqlUpdateSala->execute([$id_sala]);

// Registrar los jugadores en la partida
$sqlInsertUsuarioPartida = $con->prepare("
    INSERT INTO usuario_partida (id_partida, documento, vida_restante, puntos_acumulados, eliminado)
    VALUES (?, ?, 1000, 0, 0)
");

foreach ($jugadores as $j) {
    $sqlInsertUsuarioPartida->execute([$id_partida, $j['documento']]);
}

// Redirigir a la partida
header("Location: partida.php?id_partida=" . $id_partida);
exit;
?>
