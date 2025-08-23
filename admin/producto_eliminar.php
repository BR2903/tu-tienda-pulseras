<?php
session_start();
require_once '../conection/db.php';

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

// Verificar que no está en pedidos
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM detalle_pedidos WHERE producto_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

if ($total > 0) {
    // No puedes borrar si está en algún pedido
    header('Location: productos_list.php?error=asociados');
    exit;
}

$stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();
header('Location: productos_list.php');
exit;
?>