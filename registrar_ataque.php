<?php
session_start();
require_once("config/database.php");

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['documento'])) exit("Sesión no válida.");

$documento = $_SESSION['documento'];
$id_partida = intval($_POST['id_partida'] ?? 0);
$id_arma = intval($_POST['id_arma'] ?? 0);
$parte = $_POST['parte'] ?? 'torso';
$objetivo = trim($_POST['objetivo'] ?? '');

if ($id_partida <= 0 || $id_arma <= 0 || empty($objetivo)) {
    exit("Datos inválidos del ataque.");
}

try {
    $con->beginTransaction();

    // 🔹 Daño del arma
    $sqlArma = $con->prepare("SELECT daño FROM armas WHERE id_arma = ?");
    $sqlArma->execute([$id_arma]);
    $arma = $sqlArma->fetch(PDO::FETCH_ASSOC);
    if (!$arma) throw new Exception("Arma no encontrada.");
    $daño_base = $arma['daño'];

    // 🔹 Multiplicador por parte del cuerpo
    $mult = match($parte) {
        'cabeza' => 2.0,
        'piernas' => 0.5,
        default => 1.0
    };
    $daño_total = $daño_base * $mult;

    // 🔹 Restar vida al objetivo
    $sql = $con->prepare("
        UPDATE usuario_partida
        SET vida_restante = GREATEST(vida_restante - ?, 0)
        WHERE id_partida = ? AND documento = ?
    ");
    $sql->execute([$daño_total, $id_partida, $objetivo]);

    // 🔹 Sumar puntos al atacante
    $sql = $con->prepare("
        UPDATE usuario_partida
        SET puntos_acumulados = puntos_acumulados + ?
        WHERE id_partida = ? AND documento = ?
    ");
    $sql->execute([$daño_total, $id_partida, $documento]);

    // 🔹 Verificar si el objetivo fue eliminado
    $sql = $con->prepare("SELECT vida_restante FROM usuario_partida WHERE id_partida=? AND documento=?");
    $sql->execute([$id_partida, $objetivo]);
    $vida = $sql->fetchColumn();

    if ($vida !== false && $vida <= 0) {
        $sql = $con->prepare("UPDATE usuario_partida SET eliminado = 1 WHERE id_partida = ? AND documento = ?");
        $sql->execute([$id_partida, $objetivo]);
    }

    $con->commit();

    // ✅ Respuesta en texto simple para AJAX
    echo "UPDATE";
} catch (Exception $e) {
    $con->rollBack();
    echo "ERROR: " . $e->getMessage();
}
?>
