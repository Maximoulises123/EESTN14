<?php
require_once '../src/config.php';

// Verificar si el usuario es administrador o director
if (!isset($_SESSION['tipo']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'director')) {
    header('Location: login.php');
    exit;
}

// Procesar acciones
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'generar_numero_comunicado') {
        $año = $_POST['año'] ?? date('Y');
        
        // Obtener el último número de comunicado del año
        $stmt = $pdo->prepare("SELECT MAX(numero_comunicado) as ultimo_numero FROM comunicado WHERE año = ?");
        $stmt->execute([$año]);
        $result = $stmt->fetch();
        
        $siguiente_numero = ($result['ultimo_numero'] ?? 0) + 1;
        
        echo json_encode(['numero' => $siguiente_numero]);
        exit;
    }
    
    if ($action === 'realizar_sorteo') {
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
            
            echo json_encode(['success' => true, 'seleccionados' => count($seleccionados)]);
            exit;
        }
    }
}

// Obtener estadísticas
$stats = [];

// Estadísticas de comunicados
$stmt = $pdo->query("SELECT COUNT(*) as total FROM comunicado");
$stats['total_comunicados'] = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM comunicado WHERE destacado = 1");
$stats['comunicados_destacados'] = $stmt->fetch()['total'];

// Estadísticas de proyectos
$stmt = $pdo->query("SELECT COUNT(*) as total FROM proyectos");
$stats['total_proyectos'] = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT categoria, COUNT(*) as total FROM proyectos GROUP BY categoria");
$stats['proyectos_por_categoria'] = $stmt->fetchAll();

// Estadísticas de preinscripciones
$stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM preinsicripcion GROUP BY estado");
$stats['preinscripciones_por_estado'] = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - EEST14</title>
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
        
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .admin-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
        }
        
        .admin-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        
        .admin-card.destacado {
            border-left-color: #e74c3c;
        }
        
        .admin-card.exito {
            border-left-color: #27ae60;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
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
        
        .btn-admin {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-admin:hover {
            background: #2980b9;
        }
        
        .btn-admin.danger {
            background: #e74c3c;
        }
        
        .btn-admin.danger:hover {
            background: #c0392b;
        }
        
        .btn-admin.success {
            background: #27ae60;
        }
        
        .btn-admin.success:hover {
            background: #229954;
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
        
        .sorteo-result {
            margin-top: 15px;
            padding: 15px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            display: none;
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
                <h1>Panel de Administración</h1>
                <p>Gestión integral de comunicados, proyectos y preinscripciones</p>
            </div>
            
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['total_comunicados']; ?></div>
                    <div class="stat-label">Comunicados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['total_proyectos']; ?></div>
                    <div class="stat-label">Proyectos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['comunicados_destacados']; ?></div>
                    <div class="stat-label">Destacados</div>
                </div>
                <?php 
                $total_preinscripciones = array_sum(array_column($stats['preinscripciones_por_estado'], 'total'));
                ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_preinscripciones; ?></div>
                    <div class="stat-label">Preinscripciones</div>
                </div>
            </div>
            
            <!-- Grid de administración -->
            <div class="admin-grid">
                <!-- Gestión de Comunicados -->
                <div class="admin-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            <path d="M13 8H7"/>
                            <path d="M17 12H7"/>
                        </svg>
                        Gestión de Comunicados
                    </h3>
                    <p>Administra los comunicados oficiales de la institución</p>
                    <a href="publicar_comunicado.php" class="btn-admin">Publicar Comunicado</a>
                    <a href="editar_comunicados.php" class="btn-admin">Gestionar Comunicados</a>
                    <button onclick="generarNumeroComunicado()" class="btn-admin">Generar Número</button>
                </div>
                
                <!-- Gestión de Proyectos -->
                <div class="admin-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                            <path d="M12 11h4"/>
                            <path d="M12 16h4"/>
                            <path d="M8 11h.01"/>
                            <path d="M8 16h.01"/>
                        </svg>
                        Gestión de Proyectos
                    </h3>
                    <p>Administra proyectos por categorías</p>
                    <a href="gestionar_proyectos.php" class="btn-admin">Gestionar Proyectos</a>
                    <a href="proyectos_destacados.php" class="btn-admin success">Proyectos Destacados</a>
                </div>
                
                <!-- Sorteo de Preinscripciones -->
                <div class="admin-card destacado">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                        </svg>
                        Sorteo de Preinscripciones
                    </h3>
                    <p>Realiza sorteos para seleccionar estudiantes</p>
                    <button onclick="abrirModalSorteo()" class="btn-admin danger">Realizar Sorteo</button>
                    <a href="gestionar_preinscripciones.php" class="btn-admin">Ver Preinscripciones</a>
                </div>
                
                <!-- Estadísticas Detalladas -->
                <div class="admin-card exito">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M18 20V10"/>
                            <path d="M12 20V4"/>
                            <path d="M6 20v-6"/>
                        </svg>
                        Estadísticas
                    </h3>
                    <p>Visualiza estadísticas detalladas</p>
                    <h4>Proyectos por Categoría:</h4>
                    <?php foreach ($stats['proyectos_por_categoria'] as $categoria): ?>
                        <p><strong><?php echo ucfirst($categoria['categoria']); ?>:</strong> <?php echo $categoria['total']; ?></p>
                    <?php endforeach; ?>
                    
                    <h4>Preinscripciones:</h4>
                    <?php foreach ($stats['preinscripciones_por_estado'] as $estado): ?>
                        <p><strong><?php echo ucfirst($estado['estado']); ?>:</strong> <?php echo $estado['total']; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal para Sorteo -->
    <div id="modalSorteo" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalSorteo()">&times;</span>
            <h3>Realizar Sorteo de Preinscripciones</h3>
            <form id="formSorteo">
                <div class="form-group">
                    <label for="cantidad">Cantidad de estudiantes a seleccionar:</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" required>
                </div>
                <button type="submit" class="btn-admin success">Realizar Sorteo</button>
            </form>
            <div id="resultadoSorteo" class="sorteo-result"></div>
        </div>
    </div>
    
    <!-- Modal para Número de Comunicado -->
    <div id="modalNumeroComunicado" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalNumeroComunicado()">&times;</span>
            <h3>Generar Número de Comunicado</h3>
            <form id="formNumeroComunicado">
                <div class="form-group">
                    <label for="año">Año:</label>
                    <input type="number" id="año" name="año" value="<?php echo date('Y'); ?>" min="2020" max="2030" required>
                </div>
                <button type="submit" class="btn-admin">Generar Número</button>
            </form>
            <div id="resultadoNumero" class="sorteo-result"></div>
        </div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Panel de Administración</p>
                </div>
            </div>
            <div class="footer-separator"></div>
            <div class="footer-bottom">
                <p>© 2024 E.E.S.T. N°14</p>
            </div>
        </div>
    </footer>
    
    <script>
        function abrirModalSorteo() {
            document.getElementById('modalSorteo').style.display = 'block';
        }
        
        function cerrarModalSorteo() {
            document.getElementById('modalSorteo').style.display = 'none';
        }
        
        function generarNumeroComunicado() {
            document.getElementById('modalNumeroComunicado').style.display = 'block';
        }
        
        function cerrarModalNumeroComunicado() {
            document.getElementById('modalNumeroComunicado').style.display = 'none';
        }
        
        // Formulario de sorteo
        document.getElementById('formSorteo').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'realizar_sorteo');
            formData.append('cantidad', document.getElementById('cantidad').value);
            
            fetch('admin_panel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('resultadoSorteo').innerHTML = 
                        `<strong>¡Sorteo realizado exitosamente!</strong><br>
                         Se seleccionaron ${data.seleccionados} estudiantes.`;
                    document.getElementById('resultadoSorteo').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Formulario de número de comunicado
        document.getElementById('formNumeroComunicado').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'generar_numero_comunicado');
            formData.append('año', document.getElementById('año').value);
            
            fetch('admin_panel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('resultadoNumero').innerHTML = 
                    `<strong>Número generado:</strong> ${data.numero}/${document.getElementById('año').value}`;
                document.getElementById('resultadoNumero').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modalSorteo = document.getElementById('modalSorteo');
            const modalNumero = document.getElementById('modalNumeroComunicado');
            
            if (event.target === modalSorteo) {
                modalSorteo.style.display = 'none';
            }
            if (event.target === modalNumero) {
                modalNumero.style.display = 'none';
            }
        }
    </script>
</body>
</html>
