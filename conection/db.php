<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pulseras_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log("Error de conexión a la base de datos: " . $conn->connect_error);
    http_response_code(500);
    echo "<h1>Error del servidor</h1><p>Estamos experimentando problemas técnicos. Por favor, inténtalo de nuevo más tarde.</p>";
    exit();
}
?>