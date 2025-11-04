<?php
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_GET['sala']);

// 🔹 Consultar el estado de la sala
$sql = $con->prepare("SELECT estado FROM salas WHERE id_sala = :id_sala");
$sql->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql->execute();

$data = $sql->fetch(PDO::FETCH_ASSOC);

// 🔹 Si la sala está en partida, informar al cliente
if ($data && $data['estado'] === 'en_partida') {
    echo "start";
}
?>
