<?php
session_start();
require_once("config/database.php");

// Conexión con PDO
$db = new Database();
$con = $db->conectar();

// Verificar sesión
if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$documento = $_SESSION['documento'];
$id_sala = intval($_POST['id_sala'] ?? 0);
$id_mundo = intval($_POST['id_mundo'] ?? 0);

if ($id_sala <= 0) {
    die("Sala inválida.");
}

try {
    // Iniciar transacción por seguridad
    $con->beginTransaction();

    // Eliminar usuario de la sala
    $sqlDeleteUsuario = $con->prepare("DELETE FROM sala_usuarios WHERE id_sala = ? AND documento = ?");
    $sqlDeleteUsuario->execute([$id_sala, $documento]);

    // Restar 1 al contador de jugadores
    $sqlUpdateJugadores = $con->prepare("
        UPDATE salas
        SET jugadores_actuales = GREATEST(jugadores_actuales - 1, 0)
        WHERE id_sala = ?
    ");
    $sqlUpdateJugadores->execute([$id_sala]);

    // Consultar cantidad actual de jugadores
    $sqlCheck = $con->prepare("SELECT jugadores_actuales FROM salas WHERE id_sala = ?");
    $sqlCheck->execute([$id_sala]);
    $info = $sqlCheck->fetch(PDO::FETCH_ASSOC);

    // Si ya no hay jugadores, eliminar la sala
    if ($info && intval($info['jugadores_actuales']) === 0) {
        $sqlDeleteSala = $con->prepare("DELETE FROM salas WHERE id_sala = ?");
        $sqlDeleteSala->execute([$id_sala]);
    }

    // Confirmar cambios
    $con->commit();

    // Redirigir
    header("Location: paginas/sala.php?mundo=$id_mundo");
    exit;

} catch (PDOException $e) {
    $con->rollBack();
    die("Error al salir de la sala: " . $e->getMessage());
}
?>
