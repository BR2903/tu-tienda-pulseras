<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Pulseras</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header>
    <div class="contenedor-header">
        <a href="index.php">
            <img src="img/logo.jpg" alt="Logo de la tienda" class="logo">
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="catalogo.php">Catálogo</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <?php if (isset($_SESSION['usuario_email'])): ?>
                    <?php if ($_SESSION['usuario_email'] === 'amayabryan579@gmail.com'): ?>
                        <li><a href="admin/">Panel de admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main>