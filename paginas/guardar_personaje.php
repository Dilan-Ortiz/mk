<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['documento'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_personaje'])) {
    $id_personaje = $_POST['id_personaje'];
    $documento = $_SESSION['documento'];

    $db = new Database();
    $con = $db->conectar();

    $sql = $con->prepare("UPDATE usuario SET id_personaje = :id_personaje WHERE documento = :documento");
    $sql->bindParam(':id_personaje', $id_personaje);
    $sql->bindParam(':documento', $documento);
    $sql->execute();


    header("Location: ../lobby.php");
    exit;
}
?>
