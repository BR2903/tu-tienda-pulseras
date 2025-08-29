<?php
session_start();
require_once 'proteger_admin.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Pulseras Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h2 class="mb-4 text-center">Panel de Administración</h2>
                    <p class="text-center mb-4">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Admin'); ?></strong>.</p>
                    <div class="row g-4 text-center">
                        <div class="col-md-4">
                            <a href="productos_list.php" class="btn btn-primary w-100 py-3">
                                <i class="bi bi-box-seam"></i><br>
                                Productos
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="categorias_list.php" class="btn btn-success w-100 py-3">
                                <i class="bi bi-tags"></i><br>
                                Categorías
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="materiales_list.php" class="btn btn-warning w-100 py-3">
                                <i class="bi bi-gem"></i><br>
                                Materiales
                            </a>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="text-center">
                        <a href="../index.php" class="btn btn-outline-secondary">Ir a la tienda</a>
                        <a href="../logout.php" class="btn btn-outline-danger ms-2">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>