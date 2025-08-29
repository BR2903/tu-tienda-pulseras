<?php
session_start();
require_once 'conection/db.php';

// Inicializa el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Lógica para agregar productos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    $productoId = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad'] ?? 1);

    // Si el producto ya está en el carrito, actualiza la cantidad
    if (isset($_SESSION['carrito'][$productoId])) {
        $_SESSION['carrito'][$productoId] += $cantidad;
    } else {
        // Si no está, lo agrega
        $_SESSION['carrito'][$productoId] = $cantidad;
    }
}

// Lógica para obtener detalles de los productos del carrito
$ids = array_keys($_SESSION['carrito']);
$productosCarrito = [];
$total = 0;

if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT id, nombre, precio FROM productos WHERE id IN ($placeholders)");
    $types = str_repeat('i', count($ids));
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($producto = $result->fetch_assoc()) {
        $productosCarrito[$producto['id']] = $producto;
    }
    $stmt->close();
}
$conn->close();

// Aquí iría el HTML para mostrar el carrito
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Carrito de Compras</title>
</head>
<body>
    <h1>Tu Carrito de Compras</h1>
    <?php if (empty($_SESSION['carrito'])): ?>
        <p>Tu carrito está vacío.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrito'] as $id => $cantidad): ?>
                    <?php if (isset($productosCarrito[$id])): ?>
                        <?php
                            $producto = $productosCarrito[$id];
                            $subtotal = $producto['precio'] * $cantidad;
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= $cantidad ?></td>
                            <td>$<?= htmlspecialchars($producto['precio']) ?></td>
                            <td>$<?= number_format($subtotal, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Total: $<?= number_format($total, 2) ?></h3>
        <a href="checkout.php">Proceder al pago</a>
    <?php endif; ?>
    <a href="index.php">Seguir comprando</a>
</body>
</html>