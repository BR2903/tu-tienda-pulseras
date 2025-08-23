<?php
session_start();
require_once '../conection/db.php';

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

// Verificar que no hay productos asociados
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM productos WHERE categoria_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

if ($total > 0) {
    // No puedes borrar si hay productos asociados
    header('Location: categorias_list.php?error=asociados');
    exit;
}

$stmt = $conn->prepare("DELETE FROM categorias WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();
header('Location: categorias_list.php');
exit;
?>