<?php
session_start();
require_once 'proteger_admin.php';
require_once '../conection/db.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM productos WHERE material_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

if ($total > 0) {
    header('Location: materiales_list.php?error=asociados');
    exit;
}

$stmt = $conn->prepare("DELETE FROM materiales WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$conn->close();
header('Location: materiales_list.php');
exit;
?>