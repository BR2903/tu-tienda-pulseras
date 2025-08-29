<?php
session_start();
require_once 'conection/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    // Iniciar una transacción para asegurar la integridad de los datos
    $conn->begin_transaction();
    try {
        // 1. Crear un nuevo pedido en la tabla `pedidos`
        $usuario_id = $_SESSION['usuario_id'];
        $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, estado) VALUES (?, 'pendiente')");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;
        $stmt->close();

        // 2. Insertar cada producto del carrito en la tabla `detalle_pedidos`
        $stmt_detalle = $conn->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $stmt_stock = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");

        foreach ($_SESSION['carrito'] as $producto_id => $cantidad) {
            $stmt_producto = $conn->prepare("SELECT precio, stock FROM productos WHERE id = ?");
            $stmt_producto->bind_param("i", $producto_id);
            $stmt_producto->execute();
            $producto = $stmt_producto->get_result()->fetch_assoc();
            $stmt_producto->close();

            if (!$producto || $producto['stock'] < $cantidad) {
                throw new Exception("Stock insuficiente para el producto con ID: " . $producto_id);
            }

            // Insertar el detalle del pedido
            $stmt_detalle->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $producto['precio']);
            $stmt_detalle->execute();

            // Actualizar el stock del producto
            $stmt_stock->bind_param("iii", $cantidad, $producto_id, $cantidad);
            $stmt_stock->execute();
        }

        $stmt_detalle->close();
        $stmt_stock->close();

        // 3. Si todo fue bien, confirmar la transacción y vaciar el carrito
        $conn->commit();
        unset($_SESSION['carrito']);
        $success_message = "¡Tu pedido ha sido realizado con éxito!";

    } catch (Exception $e) {
        // Si algo falla, revertir los cambios
        $conn->rollback();
        $error = "Error al procesar el pedido: " . $e->getMessage();
    }
}
$conn->close();

// Aquí iría el HTML para la página de checkout
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Finalizar Compra</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <a href="index.php">Volver a la tienda</a>
    <?php else: ?>
        <?php if (empty($_SESSION['carrito'])): ?>
            <p>No hay productos en el carrito para procesar.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>