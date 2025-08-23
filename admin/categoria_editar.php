<?php
session_start();
require_once '../conection/db.php';

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$error = "";

$stmt = $conn->prepare("SELECT * FROM categorias WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$categoria) {
    die("Categoría no encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    if ($nombre === "") {
        $error = "El nombre es obligatorio.";
    } else {
        $stmt = $conn->prepare("UPDATE categorias SET nombre=? WHERE id=?");
        $stmt->bind_param("si", $nombre, $id);
        if($stmt->execute()) {
            header('Location: categorias_list.php');
            exit;
        } else {
            $error = "Error al actualizar.";
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
    <title>Editar Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar categoría</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif ?>
    <form method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la categoría</label>
            <input type="text" name="nombre" class="form-control" id="nombre" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="categorias_list.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>