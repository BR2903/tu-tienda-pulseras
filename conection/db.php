<?php
// Configuración de la conexión
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "pulseras_db";

// Conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>