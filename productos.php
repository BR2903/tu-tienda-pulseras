<?php
session_start();
require_once 'conection/db.php';

// Obtener el ID del producto de la URL
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Buscar el producto en la base de datos de forma segura
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$conn->close();

if (!$producto) {
    // Si el producto no se encuentra, muestra un error
    die("Producto no encontrado.");
}

// Aquí iría el HTML para mostrar los detalles del producto
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= htmlspecialchars($producto['nombre']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($producto['nombre']) ?></h1>
    <img src="img/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
    <p><?= htmlspecialchars($producto['descripcion']) ?></p>
    <p>Precio: $<?= htmlspecialchars($producto['precio']) ?></p>
    <p>Stock: <?= htmlspecialchars($producto['stock']) ?></p>

    <form action="carrito.php" method="post">
        <input type="hidden" name="producto_id" value="<?= $id ?>">
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>">
        <button type="submit">Agregar al carrito</button>
    </form>
</body>
</html>