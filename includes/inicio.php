<?php
session_start();
require_once("../config/database.php");
$db = new Database();
$con = $db->conectar();

if (isset($_POST['entrar'])) {
    $username = $_POST['username'];
    $contrasena = htmlentities(addslashes($_POST['password']));

    if ($username == "" || $contrasena == "") {
        echo '<script>alert("Datos vacíos");</script>';
    } else {
        $sql = $con->prepare("SELECT * FROM usuario WHERE username = '$username'");
        $sql->execute();
        $fila = $sql->fetch();

        if ($fila) {
            if (password_verify($contrasena, $fila['password'])) {
                $_SESSION['documento'] = $fila['documento'];
                $_SESSION['username'] = $fila['username'];
                $_SESSION['password'] = $fila['password'];
                $_SESSION['nombre'] = $fila['id_role'];

                if ($_SESSION['nombre'] == 1) {
                    header("Location: /MK/admin/admin.php");
                    exit();
                }
                if ($_SESSION['nombre'] == 2) {
                    header("Location: ../lobby.php");
                    exit();
                }
            } else {
                echo '<script>alert("contraseña incorrecta");location = "index.php";</script>';

            }
        } else {
            echo '<script>alert("Correo incorrecto"); location = "index.php";</script>';
        }
    }
}
?>