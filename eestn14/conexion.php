<?php
// Conexión a la base de datos
$servidor = "localhost";
$usuario_db = "root";
$password_db = "";
$nombre_db = "eestn14";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombre_db", $usuario_db, $password_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
