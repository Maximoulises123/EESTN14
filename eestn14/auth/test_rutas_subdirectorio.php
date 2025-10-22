<?php
// Archivo de prueba para verificar el sistema de rutas desde subdirectorio
require_once '../includes/rutas.php';

echo "<h1>Prueba del Sistema de Rutas (Subdirectorio)</h1>";
echo "<hr>";

echo "<h2>Información del Script</h2>";
echo "<ul>";
echo "<li><strong>Script actual:</strong> " . $_SERVER['SCRIPT_NAME'] . "</li>";
echo "<li><strong>Base path calculado:</strong> '$base_path'</li>";
echo "<li><strong>Niveles de profundidad:</strong> $levels_deep</li>";
echo "</ul>";
echo "<hr>";

echo "<h2>Pruebas de Rutas</h2>";
echo "<ul>";
echo "<li><strong>index.php:</strong> " . $base_path . "index.php</li>";
echo "<li><strong>Logo:</strong> " . $base_path . "assets/img/logo/LOGO.png</li>";
echo "<li><strong>CSS global:</strong> " . $base_path . "assets/css/global.css</li>";
echo "<li><strong>Login:</strong> " . $base_path . "auth/login.php</li>";
echo "<li><strong>Comunicados:</strong> " . $base_path . "institucional/comunicados.php</li>";
echo "</ul>";
echo "<hr>";

echo "<h2>Test Visual</h2>";
echo "<p>Si ves el logo aquí abajo, el sistema funciona:</p>";
echo "<img src='" . $base_path . "assets/img/logo/LOGO.png' alt='Logo' style='width:200px;'>";
echo "<hr>";

echo "<p><a href='../test_rutas.php'>Ver prueba desde raíz</a></p>";
?>

