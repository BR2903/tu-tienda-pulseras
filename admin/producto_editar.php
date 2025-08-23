<?php
session_start();
require_once '../conection/db.php';

if (!isset($_SESSION['usuario_email']) || $_SESSION['usuario_email'] !== 'amayabryan579@gmail.com') {
    header('Location: ../index.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

// Obtener producto actual
$stmt = $conn->prepare("SELECT * FROM productos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$producto) {
    die("Producto no encontrado.");
}

// Obtener listas para selects
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

    // Manejo de imagen
    $imagen_nombre = $producto['imagen'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $dir_subida = '../img/';
        if (!is_dir($dir_subida)) {
            mkdir($dir_subida, 0777, true);
        }
        $archivo_tmp = $_FILES['imagen']['tmp_name'];
        $nombre_original = basename($_FILES['imagen']['name']);
        $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $permitidas)) {
            // Borra la anterior si existe
            if ($producto['imagen'] && file_exists($dir_subida . $producto['imagen'])) {
                unlink($dir_subida . $producto['imagen']);
            }
            $imagen_nombre = uniqid('img_') . '.' . $ext;
            move_uploaded_file($archivo_tmp, $dir_subida . $imagen_nombre);
        } else {
            $error = "Formato de imagen no permitido.";
        }
    }

    if ($nombre === "" || $precio <= 0 || $stock < 0) {
        $error = "Nombre, precio (mayor a 0) y stock (0 o más) son obligatorios.";
    } elseif (!$error) {
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, categoria_id=?, material_id=?, imagen=? WHERE id=?");
        $stmt->bind_param("ssdiiisi", $nombre, $descripcion, $precio, $stock, $categoria_id, $material_id, $imagen_nombre, $id);
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
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" step="0.01" min="0" value="<?= $producto['precio'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" min="0" value="<?= $producto['stock'] ?>" required>
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