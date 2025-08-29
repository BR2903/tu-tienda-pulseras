<?php
$admin_email = 'amayabryan579@gmail.com'; 

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== $admin_email) {
    header('Location: ../index.php');
    exit;
}
?>