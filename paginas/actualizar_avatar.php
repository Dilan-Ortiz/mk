<?php
session_start();
require_once '../config/database.php';
$db = new Database();
$con = $db->conectar();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc = $_SESSION['documento'];
    $id_avatar = $_POST['id_avatar'];

    if (!$doc || !$id_avatar) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        exit;
    }

    $stmt = $con->prepare("UPDATE usuario SET id_avatar = ? WHERE documento = ?");
    $ok = $stmt->execute([$id_avatar, $doc]);

    if ($ok) {
        $query = $con->prepare("SELECT avatar_foto FROM avatar WHERE id_avatar = ?");
        $query->execute([$id_avatar]);
        $avatar = $query->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'avatar' => $avatar['avatar_foto']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar']);
    }
}
?>
