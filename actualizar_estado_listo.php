<?php
session_start();
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['documento'])) exit;

$documento = $_SESSION['documento'];
$id_sala = intval($_POST['id_sala']);

// Alternar el estado "listo" del jugador
$sql_update = $con->prepare("
    UPDATE sala_usuarios 
    SET listo = CASE WHEN listo = 1 THEN 0 ELSE 1 END
    WHERE documento = :documento AND id_sala = :id_sala
");
$sql_update->bindParam(':documento', $documento);
$sql_update->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql_update->execute();

// Comprobar cuántos jugadores están listos
$sql_total = $con->prepare("
    SELECT COUNT(*) AS total 
    FROM sala_usuarios 
    WHERE id_sala = :id_sala AND listo = 1
");
$sql_total->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql_total->execute();
$total = $sql_total->fetch(PDO::FETCH_ASSOC)['total'];

// Si hay 5 o más listos, marcar la sala como “en partida”
if ($total >= 5) {
    $sql_estado = $con->prepare("
        UPDATE salas 
        SET estado = 'en_partida' 
        WHERE id_sala = :id_sala
    ");
    $sql_estado->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $sql_estado->execute();
}

echo "ok";
?>
