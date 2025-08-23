<?php
session_start();
require_once '../conection/db.php';

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    if ($nombre === "") {
        $error = "El nombre es obligatorio.";
    } else {
        $stmt = $conn->prepare("INSERT INTO materiales (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        if($stmt->execute()) {
            header('Location: materiales_list.php');
            exit;
        } else {
            $error = "Error al guardar.";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Agregar nuevo material</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif ?>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del material</label>
            <input type="text" name="nombre" class="form-control" id="nombre" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="materiales_list.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>