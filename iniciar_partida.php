<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "mk");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (!isset($_POST['id_sala'])) {
    die("No se recibió el ID de la sala.");
}

$id_sala = intval($_POST['id_sala']);


$sala = mysqli_query($conexion, "SELECT * FROM salas WHERE id_sala = $id_sala");
if (mysqli_num_rows($sala) == 0) {
    die("La sala no existe.");
}
$sala_datos = mysqli_fetch_assoc($sala);

$jugadores = mysqli_query($conexion, "SELECT documento FROM sala_usuarios WHERE id_sala = $id_sala");
$cantidad = mysqli_num_rows($jugadores);

if ($cantidad < 2) {
    die(" No se puede iniciar la partida con menos de 2 jugadores.");
}

mysqli_query($conexion, "
    INSERT INTO partidas (id_sala, estado, inicio)
    VALUES ($id_sala, 'en_curso', NOW())
");
$id_partida = mysqli_insert_id($conexion);

mysqli_query($conexion, "UPDATE salas SET estado='en_partida' WHERE id_sala=$id_sala");


mysqli_data_seek($jugadores, 0);
while ($j = mysqli_fetch_assoc($jugadores)) {
    $doc = mysqli_real_escape_string($conexion, $j['documento']);
    mysqli_query($conexion, "
        INSERT INTO usuario_partida (id_partida, documento, vida_restante, puntos_acumulados, eliminado)
        VALUES ($id_partida, '$doc', 1000, 0, 0)
    ");
}

header("Location: partida.php?id_partida=" . $id_partida);
exit;
?>
