<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) die("Error de conexión: " . mysqli_connect_error());

if (!isset($_SESSION['documento'])) {
    header("Location: login.php");
    exit;
}
$documento = $_SESSION['documento'];


$id_partida = intval($_POST['id_partida'] ?? 0);
$id_arma = intval($_POST['id_arma'] ?? 0);
$parte = $_POST['parte'] ?? 'torso';
$objetivo = mysqli_real_escape_string($conexion, $_POST['objetivo'] ?? '');


if ($id_partida <= 0 || $id_arma <= 0 || empty($objetivo)) {
    die(" Datos inválidos del ataque.");
}


$consulta_arma = mysqli_query($conexion, "SELECT daño FROM armas WHERE id_arma = $id_arma");
$arma = mysqli_fetch_assoc($consulta_arma);
if (!$arma) die("⚠️ Arma no encontrada.");

$daño_base = $arma['daño'];

switch ($parte) {
    case 'cabeza': $multiplicador = 2.0; break;
    case 'piernas': $multiplicador = 0.5; break;
    default: $multiplicador = 1.0;
}

$daño_total = $daño_base * $multiplicador;


mysqli_query($conexion, "
    UPDATE usuario_partida 
    SET vida_restante = GREATEST(vida_restante - $daño_total, 0)
    WHERE id_partida = $id_partida AND documento = '$objetivo'
");


mysqli_query($conexion, "
    UPDATE usuario_partida 
    SET puntos_acumulados = puntos_acumulados + $daño_total
    WHERE id_partida = $id_partida AND documento = '$documento'
");


$vida_restante = mysqli_fetch_assoc(mysqli_query($conexion, "
    SELECT vida_restante FROM usuario_partida 
    WHERE id_partida = $id_partida AND documento = '$objetivo'
"))['vida_restante'];

if ($vida_restante <= 0) {
    mysqli_query($conexion, "
        UPDATE usuario_partida 
        SET eliminado = 1 
        WHERE id_partida = $id_partida AND documento = '$objetivo'
    ");
}

header("Location: partida.php?id_partida=$id_partida");
exit;
?>
