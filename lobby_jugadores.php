<?php
require_once 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id_sala = intval($_GET['sala']);

// 🔹 Consultar jugadores de la sala con su avatar y estado "listo"
$sql = $con->prepare("
    SELECT 
        u.username, 
        a.avatar_foto, 
        su.listo
    FROM sala_usuarios su
    INNER JOIN usuario u ON su.documento = u.documento
    INNER JOIN avatar a ON u.id_avatar = a.id_avatar
    WHERE su.id_sala = :id_sala
");
$sql->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$sql->execute();

$jugadores = $sql->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Mostrar cada jugador con su imagen y estado
foreach ($jugadores as $jugador) {
    echo '<div class="avatar-card">';
    echo '<img src="../' . htmlspecialchars($jugador['avatar_foto']) . '" alt="Avatar">';
    echo '<span>' . htmlspecialchars($jugador['username']) . '</span>';

    if (!empty($jugador['listo'])) {
        echo '<div class="estado-listo">Listo</div>';
    } else {
        echo '<div class="estado-no">Esperando...</div>';
    }

    echo '</div>';
}
?>
