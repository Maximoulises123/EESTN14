<?php
// Sistema de rutas dinámicas - Versión simplificada y robusta
// Calcula automáticamente cuántos niveles subir para llegar a la raíz

// Obtener la ruta del script actual
$current_script = $_SERVER['SCRIPT_NAME'];

// Contar cuántas barras "/" hay (cada una es un nivel de carpeta)
// Excluir la primera barra y el nombre del archivo
$parts = explode('/', trim($current_script, '/'));

// Quitar el nombre del archivo (último elemento)
array_pop($parts);

// Contar niveles de carpetas
$levels = count($parts);

// Generar la ruta base
if ($levels > 0) {
    $base_path = str_repeat('../', $levels);
} else {
    $base_path = '';
}
?>
