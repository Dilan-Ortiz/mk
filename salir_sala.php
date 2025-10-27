<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) die("Error de conexión: " . mysqli_connect_error());

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$documento = $_SESSION['documento'];
$id_sala = intval($_POST['id_sala'] ?? 0);
$id_mundo = intval($_POST['id_mundo'] ?? 0);

if ($id_sala <= 0) die("Sala inválida.");


mysqli_query($conexion, "DELETE FROM sala_usuarios WHERE id_sala=$id_sala AND documento='$documento'");


mysqli_query($conexion, "UPDATE salas SET jugadores_actuales = GREATEST(jugadores_actuales - 1, 0) WHERE id_sala=$id_sala");


$check = mysqli_query($conexion, "SELECT jugadores_actuales FROM salas WHERE id_sala=$id_sala");
$info = mysqli_fetch_assoc($check);
if ($info && intval($info['jugadores_actuales']) == 0) {
    mysqli_query($conexion, "DELETE FROM salas WHERE id_sala=$id_sala");
}

header("Location: paginas/sala.php?mundo=$id_mundo");
exit;
?>
