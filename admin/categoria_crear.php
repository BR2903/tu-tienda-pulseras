<?php
session_start();
require_once 'proteger_admin.php';
require_once '../conection/db.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    if ($nombre === "") {
        $error = "El nombre es obligatorio.";
    } else {
        $stmt = $conn->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        if($stmt->execute()) {
            header('Location: categorias_list.php');
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
    <title>Nueva Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Agregar nueva categoría</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif ?>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la categoría</label>
            <input type="text" name="nombre" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="categorias_list.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>