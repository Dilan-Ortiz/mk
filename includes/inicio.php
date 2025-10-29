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

            if($fila['id_estado'] == 2){
                echo '<script>alert("Usuario se encuentra bloqueado, espere que el administrador active su cuenta"); location = "/mk/index.html";</script>';
                exit;
            }

            if (password_verify($contrasena, $fila['password'])) {
                $_SESSION['documento'] = $fila['documento'];
                $_SESSION['username'] = $fila['username'];
                $_SESSION['password'] = $fila['password'];
                $_SESSION['nombre'] = $fila['id_role'];


                $fecha_actual = date("Y-m-d h:i:s");
                $fechasql = $con->prepare("UPDATE usuario SET ultimo_login = '$fecha_actual' WHERE username = '$username'");
                $fechasql->execute();

                if ($_SESSION['nombre'] == 1) {
                    header("Location: /MK/admin/admin.php");
                    exit();
                }
                if ($_SESSION['nombre'] == 2) {
                    header("Location: ../lobby.php");
                    exit();
                }
            } else {
                echo '<script>alert("contraseña incorrecta");location = "/mk/index.html";</script>';

            }
        } else {
            echo '<script>alert("nombre de usuario incorrecto"); location = "/mk/index.html";</script>';
        }
    }
}
?>