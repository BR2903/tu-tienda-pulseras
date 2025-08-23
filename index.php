<?php
session_start();
require_once 'conection/db.php';

// Consulta de productos
$query = "SELECT * FROM productos ORDER BY id DESC";
$result = $conn->query($query);
$productos = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pulseras Shop - Catálogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .catalogo-card {
            min-height: 400px;
            max-width: 350px;
            margin: 0 auto;
        }
        .catalogo-img {
            object-fit: cover;
            height: 220px;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-primary bg-primary px-4">
        <a class="navbar-brand text-white fw-bold" href="#">Pulseras Shop</a>
        <div class="ms-auto">
            <?php if (isset($_SESSION['usuario_email'])): ?>
                <span class="me-2">👋 Hola, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? $_SESSION['usuario_email']) ?></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm me-2">Cerrar sesión</a>
                <a href="carrito.php" class="btn btn-light btn-sm me-2">🛒 Carrito</a>
                <?php if ($_SESSION['usuario_email'] === 'amayabryan579@gmail.com'): ?>
                    <a href="admin/productos_list.php" class="btn btn-dark btn-sm">Panel de administración</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light btn-sm">Iniciar sesión</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Catálogo de Pulseras</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col">
                    <div class="card catalogo-card shadow">
                        <?php if (!empty($producto['imagen'])): ?>
                            <img src="img/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top catalogo-img" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center catalogo-img text-muted">
                                Sin imagen
                            </div>
                        <?php endif ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                            <?php if (!empty($producto['descripcion'])): ?>
                                <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
                            <?php endif; ?>
                            <p class="card-text fw-bold">
                                Precio: $<?= number_format($producto['precio'], 2) ?>
                            </p>
                            <form method="post" action="agregar_carrito.php" class="mt-auto">
                                <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                                <button type="submit" class="btn btn-primary w-100">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <footer class="text-center mt-5 mb-3 text-muted">
        &copy; <?= date('Y') ?> Pulseras Shop. Todos los derechos reservados.
    </footer>
</body>
</html>