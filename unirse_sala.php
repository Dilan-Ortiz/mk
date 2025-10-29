<?php
session_start();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}

// Verificar sesión
if (!isset($_SESSION['documento']) || empty($_SESSION['documento'])) {
    die("⚠️ No hay documento en sesión. Inicia sesión nuevamente.");
}
$documento = $_SESSION['documento'];

// Verificar parámetro sala
if (!isset($_GET['sala'])) {
    die("⚠️ No se seleccionó ninguna sala.");
}
$id_sala = intval($_GET['sala']);

// Buscar la sala (permitir 'abierta' y 'en_partida')
$stmt = $conexion->prepare("SELECT * FROM salas WHERE id_sala = ? AND estado IN ('abierta', 'en_partida')");
$stmt->bind_param("i", $id_sala);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // Mostrar el estado real si existe (para debug)
    $debug = $conexion->query("SELECT estado FROM salas WHERE id_sala = $id_sala")->fetch_assoc();
    $estado = $debug['estado'] ?? 'no encontrada';
    die("❌ La sala no existe o está cerrada. (Estado actual: $estado)");
}
$sala = $result->fetch_assoc();
$stmt->close();

// Contar jugadores actuales en sala_usuarios
$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM sala_usuarios WHERE id_sala = ?");
$stmt->bind_param("i", $id_sala);
$stmt->execute();
$res = $stmt->get_result();
$fila = $res->fetch_assoc();
$total = (int)$fila['total'];
$stmt->close();

// Validar cupos
if ($total >= (int)$sala['max_jugadores']) {
    die("⚠️ La sala está llena.");
}

// Verificar si el usuario ya está en la sala
$stmt = $conexion->prepare("SELECT id_sala_usuario FROM sala_usuarios WHERE documento = ? AND id_sala = ?");
$stmt->bind_param("si", $documento, $id_sala);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    // Insertar usuario en la sala
    $stmt = $conexion->prepare("INSERT INTO sala_usuarios (id_sala, documento) VALUES (?, ?)");
    $stmt->bind_param("is", $id_sala, $documento);
    if (!$stmt->execute()) {
        die("❌ Error al unirse a la sala: " . $stmt->error);
    }
    $stmt->close();

    // Actualizar contador de jugadores
    $stmt = $conexion->prepare("UPDATE salas SET jugadores_actuales = jugadores_actuales + 1 WHERE id_sala = ?");
    $stmt->bind_param("i", $id_sala);
    $stmt->execute();
    $stmt->close();
}

// Redirigir al lobby de la partida
header("Location: lobby_partida.php?sala=" . $id_sala);
exit;
?>
