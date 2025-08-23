<?php
session_start();
$admin_email = 'amayabryan579@gmail.com'; // CAMBIA esto por tu correo real

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== $admin_email) {
    // Si no está logueado o no es el admin, lo redirige
    header('Location: ../index.php');
    exit;
}
?>