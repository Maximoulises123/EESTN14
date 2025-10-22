<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Rutas - SUBDIRECTORIO</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; max-width: 800px; margin: 0 auto; }
        h1 { color: #d32f2f; border-bottom: 2px solid #d32f2f; padding-bottom: 10px; }
        .info { background: #fff3e0; padding: 15px; margin: 10px 0; border-radius: 4px; }
        .success { background: #c8e6c9; padding: 15px; margin: 10px 0; border-radius: 4px; }
        .error { background: #ffcdd2; padding: 15px; margin: 10px 0; border-radius: 4px; }
        pre { background: #263238; color: #aed581; padding: 15px; border-radius: 4px; overflow-x: auto; }
        img { border: 2px solid #ddd; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug del Sistema de Rutas - DESDE SUBDIRECTORIO</h1>
        
        <?php
        require_once '../includes/rutas.php';
        
        echo '<div class="info">';
        echo '<h2>📍 Información del Script</h2>';
        echo '<strong>Script actual:</strong> ' . $_SERVER['SCRIPT_NAME'] . '<br>';
        echo '<strong>Base path calculado:</strong> <code>' . htmlspecialchars($base_path) . '</code><br>';
        echo '<strong>Debe ser:</strong> <code>\'../\'</code> (un nivel arriba)';
        echo '</div>';
        
        echo '<div class="info">';
        echo '<h2>🔗 Rutas Generadas</h2>';
        echo '<strong>Logo:</strong> <code>' . $base_path . 'assets/img/logo/LOGO.png</code><br>';
        echo '<strong>CSS Global:</strong> <code>' . $base_path . 'assets/css/global.css</code><br>';
        echo '<strong>Index:</strong> <code>' . $base_path . 'index.php</code><br>';
        echo '<strong>Comunicados:</strong> <code>' . $base_path . 'institucional/comunicados.php</code>';
        echo '</div>';
        
        $logo_path = $base_path . 'assets/img/logo/LOGO.png';
        
        if (file_exists('../assets/img/logo/LOGO.png')) {
            echo '<div class="success">';
            echo '<h2>✅ PRUEBA EXITOSA</h2>';
            echo '<p><strong>El logo existe y se puede cargar:</strong></p>';
            echo '<img src="' . $logo_path . '" alt="Logo EEST14" style="max-width: 300px;">';
            echo '</div>';
        } else {
            echo '<div class="error">';
            echo '<h2>❌ ERROR</h2>';
            echo '<p>El logo NO se encuentra en la ruta calculada.</p>';
            echo '<p><strong>Ruta buscada:</strong> ../assets/img/logo/LOGO.png</p>';
            echo '</div>';
        }
        ?>
        
        <div class="info">
            <h2>📝 Variables PHP</h2>
            <pre><?php
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "base_path: '$base_path'\n";
            ?></pre>
        </div>
        
        <p><a href="../debug_rutas.php">← Volver a prueba desde raíz</a></p>
    </div>
</body>
</html>

