<?php
session_start();
require_once("config/database.php"); // Ajusta la ruta según tu estructura

// Conexión con PDO
$db = new Database();
$con = $db->conectar();

// Verificar sesión
if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}

$documento = $_SESSION['documento'];

// Recibir datos del ataque
$id_partida = intval($_POST['id_partida'] ?? 0);
$id_arma = intval($_POST['id_arma'] ?? 0);
$parte = $_POST['parte'] ?? 'torso';
$objetivo = trim($_POST['objetivo'] ?? '');

if ($id_partida <= 0 || $id_arma <= 0 || empty($objetivo)) {
    die("Datos inválidos del ataque.");
}

try {
    // Iniciar transacción
    $con->beginTransaction();

    // 1️⃣ Obtener daño base del arma
    $sqlArma = $con->prepare("SELECT daño FROM armas WHERE id_arma = ?");
    $sqlArma->execute([$id_arma]);
    $arma = $sqlArma->fetch(PDO::FETCH_ASSOC);

    if (!$arma) {
        throw new Exception("⚠️ Arma no encontrada.");
    }

    $daño_base = $arma['daño'];

    // 2️⃣ Calcular daño según la parte del cuerpo
    switch ($parte) {
        case 'cabeza':
            $multiplicador = 2.0;
            break;
        case 'piernas':
            $multiplicador = 0.5;
            break;
        default:
            $multiplicador = 1.0;
    }

    $daño_total = $daño_base * $multiplicador;

    // 3️⃣ Restar vida al jugador objetivo
    $sqlRestarVida = $con->prepare("
        UPDATE usuario_partida 
        SET vida_restante = GREATEST(vida_restante - ?, 0)
        WHERE id_partida = ? AND documento = ?
    ");
    $sqlRestarVida->execute([$daño_total, $id_partida, $objetivo]);

    // 4️⃣ Sumar puntos al atacante
    $sqlSumarPuntos = $con->prepare("
        UPDATE usuario_partida 
        SET puntos_acumulados = puntos_acumulados + ?
        WHERE id_partida = ? AND documento = ?
    ");
    $sqlSumarPuntos->execute([$daño_total, $id_partida, $documento]);

    // 5️⃣ Consultar vida restante del objetivo
    $sqlVida = $con->prepare("
        SELECT vida_restante 
        FROM usuario_partida 
        WHERE id_partida = ? AND documento = ?
    ");
    $sqlVida->execute([$id_partida, $objetivo]);
    $vida = $sqlVida->fetch(PDO::FETCH_ASSOC);

    if ($vida && $vida['vida_restante'] <= 0) {
        // 6️⃣ Marcar jugador como eliminado
        $sqlEliminar = $con->prepare("
            UPDATE usuario_partida 
            SET eliminado = 1
            WHERE id_partida = ? AND documento = ?
        ");
        $sqlEliminar->execute([$id_partida, $objetivo]);
    }

    // Confirmar todo
    $con->commit();

    // Redirigir de vuelta a la partida
    header("Location: partida.php?id_partida=$id_partida");
    exit;

} catch (Exception $e) {
    $con->rollBack();
    die("Error en el ataque: " . $e->getMessage());
}
?>
