<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pulseras_db"; // El nombre de tu base de datos

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>