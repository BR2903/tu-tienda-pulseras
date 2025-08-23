<?php
session_start();
require_once '../conection/db.php';

// Solo admin
if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

// Obtener materiales
$stmt = $conn->prepare("SELECT * FROM materiales ORDER BY id DESC");
$stmt->execute();
$materiales = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Materiales - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Materiales</h2>
    <a href="material_crear.php" class="btn btn-success mb-3">+ Nuevo Material</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($materiales as $mat): ?>
            <tr>
                <td><?= $mat['id'] ?></td>
                <td><?= htmlspecialchars($mat['nombre']) ?></td>
                <td>
                    <a href="material_editar.php?id=<?= $mat['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="material_eliminar.php?id=<?= $mat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar? Solo podrás eliminar si no hay productos asociados.');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <a href="productos_list.php" class="btn btn-secondary">Volver al panel</a>
</div>
</body>
</html>