<?php
include 'templates/header.php';
include_once 'conection/db.php';

// Obtener categorías y materiales para los filtros
$categorias = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);
$materiales = $conn->query("SELECT id, nombre FROM materiales ORDER BY nombre ASC")->fetch_all(MYSQLI_ASSOC);

// Construir la consulta SQL con filtros
$sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
$params = [];
$types = "";
$where = [];

// Obtener los valores de los filtros desde la URL
$categoria_id = intval($_GET['categoria'] ?? 0);
$material_id = intval($_GET['material'] ?? 0);

if ($categoria_id > 0) {
    $where[] = "categoria_id = ?";
    $params[] = $categoria_id;
    $types .= "i";
}

if ($material_id > 0) {
    $where[] = "material_id = ?";
    $params[] = $material_id;
    $types .= "i";
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$productos = [];
if ($result) {
    $productos = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
$conn->close();
?>

<section class="catalogo-con-filtros">
    <h1 class="text-center my-4">Explora nuestro Catálogo</h1>
    <div class="row">
        <div class="col-md-3">
            <h3>Filtros</h3>
            <form action="catalogo.php" method="get">
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categoria_id == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <select class="form-select" id="material" name="material">
                        <option value="">Todos</option>
                        <?php foreach ($materiales as $mat): ?>
                            <option value="<?= $mat['id'] ?>" <?= $material_id == $mat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($mat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Aplicar Filtros</button>
            </form>
        </div>

        <div class="col-md-9">
            <div class="lista-productos">
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $prod): ?>
                        <a href="productos.php?id=<?= $prod['id'] ?>">
                            <div class="producto">
                                <img src="img/<?= htmlspecialchars($prod['imagen']) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                                <h3><?= htmlspecialchars($prod['nombre']) ?></h3>
                                <p><?= htmlspecialchars($prod['descripcion']) ?></p>
                                <p class="precio">$<?= htmlspecialchars(number_format($prod['precio'], 2)) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron productos con los filtros seleccionados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?>