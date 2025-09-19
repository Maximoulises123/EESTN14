<?php
require_once '../src/config.php';

// Verificar si está logueado y es administrador o director
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'director')) {
    header('Location: login.php');
    exit;
}

$mensaje = '';

// Procesar formularios
if ($_POST) {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'cambiar_estado':
                $id = $_POST['id'] ?? '';
                $estado = $_POST['estado'] ?? '';
                $inscripto = isset($_POST['inscripto']) ? 1 : 0;
                
                if ($id && $estado) {
                    $stmt = $pdo->prepare("UPDATE preinsicripcion SET estado = ?, inscripto = ? WHERE Id = ?");
                    $stmt->execute([$estado, $inscripto, $id]);
                    $mensaje = 'Estado actualizado correctamente';
                }
                break;
                
            case 'realizar_sorteo':
                $cantidad = $_POST['cantidad'] ?? 0;
                
                if ($cantidad > 0) {
                    // Obtener preinscripciones pendientes
                    $stmt = $pdo->query("SELECT * FROM preinsicripcion WHERE estado = 'pendiente' ORDER BY RAND() LIMIT $cantidad");
                    $seleccionados = $stmt->fetchAll();
                    
                    // Actualizar estado de los seleccionados
                    foreach ($seleccionados as $seleccionado) {
                        $stmt = $pdo->prepare("UPDATE preinsicripcion SET estado = 'seleccionado', inscripto = 1 WHERE Id = ?");
                        $stmt->execute([$seleccionado['Id']]);
                    }
                    
                    $mensaje = "Sorteo realizado exitosamente. Se seleccionaron " . count($seleccionados) . " estudiantes.";
                }
                break;
                
            case 'reiniciar_sorteo':
                $stmt = $pdo->prepare("UPDATE preinsicripcion SET estado = 'pendiente', inscripto = 0");
                $stmt->execute();
                $mensaje = 'Sorteo reiniciado. Todos los estudiantes vuelven a estado pendiente.';
                break;
        }
    }
}

// Obtener preinscripciones
$stmt = $pdo->query("SELECT * FROM preinsicripcion ORDER BY Id DESC");
$preinscripciones = $stmt->fetchAll();

// Obtener estadísticas
$stats = [];
$stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM preinsicripcion GROUP BY estado");
$stats_estado = $stmt->fetchAll();

foreach ($stats_estado as $stat) {
    $stats[$stat['estado']] = $stat['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Preinscripciones - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .admin-header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .section h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn.success {
            background: #27ae60;
        }
        
        .btn.success:hover {
            background: #229954;
        }
        
        .btn.danger {
            background: #e74c3c;
        }
        
        .btn.danger:hover {
            background: #c0392b;
        }
        
        .btn.warning {
            background: #f39c12;
        }
        
        .btn.warning:hover {
            background: #e67e22;
        }
        
        .preinscripciones-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .preinscripciones-table th,
        .preinscripciones-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .preinscripciones-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .estado-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
        }
        
        .estado-seleccionado {
            background: #d4edda;
            color: #155724;
        }
        
        .estado-rechazado {
            background: #f8d7da;
            color: #721c24;
        }
        
        .mensaje {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
        
        .sorteo-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="assets/img/LOGO.png" alt="E.E.S.T. N° 14 - GONZÁLEZ CATÁN" class="logo-img">
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="admin_panel.php">Panel Admin</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
            
            <div class="user-actions">
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
        </div>
    </header>
    
    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1>Gestión de Preinscripciones</h1>
                <p>Administra las preinscripciones y realiza sorteos</p>
            </div>
            
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['pendiente'] ?? 0; ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['seleccionado'] ?? 0; ?></div>
                    <div class="stat-label">Seleccionados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['rechazado'] ?? 0; ?></div>
                    <div class="stat-label">Rechazados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo array_sum($stats); ?></div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            
            <!-- Sorteo -->
            <div class="section">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                        <line x1="9" y1="9" x2="9.01" y2="9"/>
                        <line x1="15" y1="9" x2="15.01" y2="9"/>
                    </svg>
                    Sistema de Sorteo
                </h3>
                <div class="sorteo-actions">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="accion" value="realizar_sorteo">
                        <div class="form-group" style="display: inline-block; margin-right: 10px;">
                            <label for="cantidad">Cantidad a seleccionar:</label>
                            <input type="number" name="cantidad" id="cantidad" min="1" max="<?php echo $stats['pendiente'] ?? 0; ?>" value="1" required>
                        </div>
                        <button type="submit" class="btn success" onclick="return confirm('¿Realizar sorteo?')">Realizar Sorteo</button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="accion" value="reiniciar_sorteo">
                        <button type="submit" class="btn warning" onclick="return confirm('¿Reiniciar sorteo? Esto cambiará todos los estudiantes a pendiente.')">Reiniciar Sorteo</button>
                    </form>
                </div>
                <p><strong>Pendientes:</strong> <?php echo $stats['pendiente'] ?? 0; ?> estudiantes disponibles para sorteo.</p>
            </div>
            
            <!-- Lista de preinscripciones -->
            <div class="section">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                        <path d="M12 11h4"/>
                        <path d="M12 16h4"/>
                        <path d="M8 11h.01"/>
                        <path d="M8 16h.01"/>
                    </svg>
                    Lista de Preinscripciones
                </h3>
                <?php if (empty($preinscripciones)): ?>
                    <p>No hay preinscripciones registradas.</p>
                <?php else: ?>
                    <table class="preinscripciones-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>DNI</th>
                                <th>Estado</th>
                                <th>Inscripto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preinscripciones as $preinscripcion): ?>
                                <tr>
                                    <td><?php echo $preinscripcion['Id']; ?></td>
                                    <td><?php echo htmlspecialchars($preinscripcion['Nombre']); ?></td>
                                    <td><?php echo $preinscripcion['DNI']; ?></td>
                                    <td>
                                        <span class="estado-badge estado-<?php echo $preinscripcion['estado']; ?>">
                                            <?php echo ucfirst($preinscripcion['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($preinscripcion['inscripto']): ?>
                                            <span style="color: green;">✓ Sí</span>
                                        <?php else: ?>
                                            <span style="color: red;">✗ No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="accion" value="cambiar_estado">
                                            <input type="hidden" name="id" value="<?php echo $preinscripcion['Id']; ?>">
                                            <select name="estado" onchange="this.form.submit()">
                                                <option value="pendiente" <?php echo $preinscripcion['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="seleccionado" <?php echo $preinscripcion['estado'] === 'seleccionado' ? 'selected' : ''; ?>>Seleccionado</option>
                                                <option value="rechazado" <?php echo $preinscripcion['estado'] === 'rechazado' ? 'selected' : ''; ?>>Rechazado</option>
                                            </select>
                                            <label style="margin-left: 10px;">
                                                <input type="checkbox" name="inscripto" <?php echo $preinscripcion['inscripto'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                                Inscripto
                                            </label>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Gestión de Preinscripciones</p>
                </div>
            </div>
            <div class="footer-separator"></div>
            <div class="footer-bottom">
                <p>© 2024 E.E.S.T. N°14</p>
            </div>
        </div>
    </footer>
</body>
</html>
