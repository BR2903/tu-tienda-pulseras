<?php
session_start();
require_once '../conection/db.php';

// Solo admin
if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

// Obtener categorías
$stmt = $conn->prepare("SELECT * FROM categorias ORDER BY id DESC");
$stmt->execute();
$categorias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Categorías</h2>
    <a href="categoria_crear.php" class="btn btn-success mb-3">+ Nueva Categoría</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categorias as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['nombre']) ?></td>
                <td>
                    <a href="categoria_editar.php?id=<?= $cat['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="categoria_eliminar.php?id=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar? Solo podrás eliminar si no hay productos asociados.');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <a href="productos_list.php" class="btn btn-secondary">Volver al panel</a>
</div>
</body>
</html>