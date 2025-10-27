<?php
$conexion = new mysqli("localhost", "root", "", "mk");
if ($conexion->connect_error) {
    die("Error al conectar con la base de datos: " . $conexion->connect_error);
}


if (isset($_POST['bloquear'])) {
    $doc = $_POST['documento'];
    $conexion->query("UPDATE usuario SET id_estado = 2 WHERE documento = '$doc'");
    header("Refresh:0");
}
if (isset($_POST['activar'])) {
    $doc = $_POST['documento'];
    $conexion->query("UPDATE usuario SET id_estado = 1 WHERE documento = '$doc'"); 
    header("Refresh:0");
}
if (isset($_POST['permitir'])) {
    $doc = $_POST['documento'];
    $conexion->query("UPDATE usuario SET id_estado = 1 WHERE documento = '$doc'"); 
    header("Refresh:0");
}
if (isset($_POST['eliminar'])) {
    $doc = $_POST['documento'];
    $conexion->query("DELETE FROM usuario WHERE documento = '$doc'");
    header("Refresh:0");
}


$sql = "SELECT u.documento, u.username, u.email, r.nombre AS rol, e.nombre AS estado, u.id_estado
        FROM usuario u
        JOIN roles r ON u.id_role = r.id_role
        JOIN estados e ON u.id_estado = e.id_estado";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Jugadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url("../img/administrador.jpg") no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .tabla-container {
            background-color: rgba(0, 0, 0, 0.75);
            border-radius: 15px;
            padding: 25px;
            margin-top: 80px;
            box-shadow: 0 0 20px #ff0000;
        }
        table {
            color: #9b3b3bff;
        }
        table thead {
            background-color: #800000;
            color: white;
        }
        .btn-accion {
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            margin: 2px;
        }
        .btn-eliminar { background-color: #c0392b; }
        .btn-bloquear { background-color: #e67e22; }
        .btn-activar { background-color: #27ae60; }
        .btn-permitir { background-color: #3498db; }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px black;
            color: white;
        }
         .volver {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background-color: #e63946;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            color: white;
            font-weight: bold;
            box-shadow: 0 0 10px #000;
            text-decoration: none;
            transition: 0.3s;
        }

        .volver:hover {
            background-color: #c1121f;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container tabla-container">
        <h2>Gestión de Jugadores</h2>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($fila = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $fila['documento']; ?></td>
                        <td><?php echo $fila['username']; ?></td>
                        <td><?php echo $fila['email']; ?></td>
                        <td><?php echo $fila['rol']; ?></td>
                        <td><?php echo $fila['estado']; ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="documento" value="<?php echo $fila['documento']; ?>">
                                
                                
                                <?php if ($fila['id_estado'] == 3) { ?>
                                    <button class="btn-accion btn-permitir" name="permitir">Permitir</button>
                                <?php } ?>
                                
                               
                                <?php if ($fila['id_estado'] == 1) {  ?>
                                    <button class="btn-accion btn-bloquear" name="bloquear">Bloquear</button>
                                <?php } ?>
                                
                                <
                                <?php if ($fila['id_estado'] == 2) {  ?>
                                    <button class="btn-accion btn-activar" name="activar">Activar</button>
                                <?php } ?>
                                
                                <button class="btn-accion btn-eliminar" name="eliminar">Eliminar</button>
                            </form>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="admin.php" class="volver"> Regresar</a>

    </div>
</body>
</html>
