<?php
include 'templates/header.php';
?>

<section class="bienvenida">
    <h1>Bienvenido a la Tienda de Pulseras</h1>
    <p>Descubre nuestra colección exclusiva de pulseras artesanales hechas a mano.</p>
</section>

<section class="productos-destacados">
    <h2>Nuestros Productos Más Recientes</h2>
    <div class="lista-productos">
        <?php
        include_once 'conection/db.php';

        // Consulta para los 8 productos más recientes
        $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos ORDER BY id DESC LIMIT 8";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<a href="productos.php?id=' . htmlspecialchars($row["id"]) . '">';
                echo '<div class="producto">';
                echo '<img src="img/' . htmlspecialchars($row["imagen"]) . '" alt="' . htmlspecialchars($row["nombre"]) . '">';
                echo '<h3>' . htmlspecialchars($row["nombre"]) . '</h3>';
                echo '<p>' . htmlspecialchars($row["descripcion"]) . '</p>';
                echo '<p class="precio">$' . htmlspecialchars(number_format($row["precio"], 2)) . '</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo "<p>No hay productos destacados en este momento.</p>";
        }

        $conn->close();
        ?>
    </div>
</section>

<?php
include 'templates/footer.php';
?>