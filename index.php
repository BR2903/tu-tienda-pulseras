<?php include 'templates/header.php'; ?>

<section class="bienvenida">
    <h1>Bienvenido a la Tienda de Pulseras</h1>
    <p>Descubre nuestra colección exclusiva de pulseras artesanales hechas a mano.</p>
</section>

<section class="catalogo">
    <h2>Catálogo de Pulseras</h2>
    <div class="lista-productos">
        <?php
        include_once 'conection/db.php';

        if (!isset($conn) || $conn === null) {
            echo "<p>Error de conexión a la base de datos.</p>";
        } else {
            $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="producto">';
                    echo '<img src="img/' . htmlspecialchars($row["imagen"]) . '" alt="' . htmlspecialchars($row["nombre"]) . '">';
                    echo '<h3>' . htmlspecialchars($row["nombre"]) . '</h3>';
                    echo '<p>' . htmlspecialchars($row["descripcion"]) . '</p>';
                    echo '<p class="precio">$' . htmlspecialchars(number_format($row["precio"], 2)) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No hay productos disponibles en este momento.</p>";
            }
        }
        ?>
    </div>
</section>

<?php include 'templates/footer.php'; ?>