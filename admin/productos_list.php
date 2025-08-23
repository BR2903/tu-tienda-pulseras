<?php
session_start();
require_once '../conection/db.php';

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

// Obtener productos con nombres de categoría y material
$query = "SELECT productos.*, categorias.nombre AS categoria, materiales.nombre AS material 
          FROM productos 
          LEFT JOIN categorias ON productos.categoria_id = categorias.id 
          LEFT JOIN materiales ON productos.material_id = materiales.id
          ORDER BY productos.id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Productos</h2>
    <a href="producto_crear.php" class="btn btn-success mb-3">+ Nuevo Producto</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Material</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $prod): ?>
            <tr>
                <td><?= $prod['id'] ?></td>
                <td>
                    <?php if (!empty($prod['imagen'])): ?>
                        <img src="../img/<?= htmlspecialchars($prod['imagen']) ?>" width="60" style="object-fit:cover;max-height:60px;">
                    <?php else: ?>
                        <span class="text-muted">Sin imagen</span>
                    <?php endif ?>
                </td>
                <td><?= htmlspecialchars($prod['nombre']) ?></td>
                <td><?= htmlspecialchars($prod['categoria']) ?></td>
                <td><?= htmlspecialchars($prod['material']) ?></td>
                <td><?= $prod['precio'] ?></td>
                <td><?= $prod['stock'] ?></td>
                <td>
                    <a href="producto_editar.php?id=<?= $prod['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="producto_eliminar.php?id=<?= $prod['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro de eliminar? Solo podrás eliminar si no está en pedidos activos.');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <a href="categorias_list.php" class="btn btn-info">Categorías</a>
    <a href="materiales_list.php" class="btn btn-info">Materiales</a>
    <a href="../index.php" class="btn btn-secondary">Volver al inicio</a>
</div>
</body>
</html>