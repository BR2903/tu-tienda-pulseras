<?php
session_start();
require_once 'proteger_admin.php';
require_once '../conection/db.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM productos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$producto) {
    die("Producto no encontrado.");
}

$categorias = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);
$materiales = $conn->query("SELECT id, nombre FROM materiales ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $categoria_id = intval($_POST['categoria_id']);
    $material_id = intval($_POST['material_id']);

    $imagen_nombre = $producto['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $dir_subida = '../img/';
        $archivo_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_original = basename($_FILES['imagen']['name']);
        $imagen_nombre_nueva = uniqid() . '_' . $nombre_original;
        $ruta_destino = $dir_subida . $imagen_nombre_nueva;

        if (move_uploaded_file($archivo_tmp, $ruta_destino)) {
            if (!empty($producto['imagen']) && file_exists($dir_subida . $producto['imagen'])) {
                unlink($dir_subida . $producto['imagen']);
            }
            $imagen_nombre = $imagen_nombre_nueva;
        } else {
            $error = "Error al subir la nueva imagen.";
        }
    }

    if ($error === "") {
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, categoria_id=?, material_id=?, imagen=? WHERE id=?");
        $stmt->bind_param("ssdiissi", $nombre, $descripcion, $precio, $stock, $categoria_id, $material_id, $imagen_nombre, $id);
        if($stmt->execute()) {
            header('Location: productos_list.php');
            exit;
        } else {
            $error = "Error al actualizar.";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar producto</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required autofocus>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" step="0.01" value="<?= htmlspecialchars($producto['precio']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" min="0" value="<?= htmlspecialchars($producto['stock']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="material_id" class="form-label">Material</label>
            <select name="material_id" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($materiales as $mat): ?>
                    <option value="<?= $mat['id'] ?>" <?= $mat['id'] == $producto['material_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mat['nombre']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (JPG, PNG, GIF, WEBP)</label>
            <?php if ($producto['imagen']): ?>
                <div>
                    <img src="../img/<?= htmlspecialchars($producto['imagen']) ?>" width="100" style="object-fit:cover;max-height:100px;">
                    <br><small>Deja vacío para no cambiar la imagen</small>
                </div>
            <?php endif ?>
            <input type="file" name="imagen" class="form-control" id="imagen" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="productos_list.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>