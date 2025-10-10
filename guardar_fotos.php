<?php
$host = "localhost";
$dbname = "mk";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['avatar_foto']) && $_FILES['avatar_foto']['error'] === UPLOAD_ERR_OK) {

            $fileTmpPath = $_FILES['avatar_foto']['tmp_name'];
            $fileName = $_FILES['avatar_foto']['name'];
            $fileSize = $_FILES['avatar_foto']['size'];
            $fileType = $_FILES['avatar_foto']['type'];

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($fileType, $allowedTypes)) {
                die(json_encode(['error' => 'Solo se permiten archivos JPEG, JPG y PNG.']));
            }

            if ($fileSize > 2 * 1024 * 1024) {
                die(json_encode(['error' => 'El tamaño del archivo no debe exceder los 2 MB.']));
            }

            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = uniqid() . '_' . $fileName;
            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                die(json_encode(['error' => 'Error al subir la imagen.']));
            }

            // Insertar en la BD
            $stmt = $pdo->prepare("INSERT INTO avatar ( avatar_foto) VALUES (?)");
            $stmt->execute([ $destPath]);

            echo json_encode(['message' => 'Datos guardados correctamente.']);

        } else {
            echo json_encode(['error' => 'No se recibió ninguna imagen o hubo un error al subirla.']);
        }

    } else {
        echo json_encode(['error' => 'Método no permitido.']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
