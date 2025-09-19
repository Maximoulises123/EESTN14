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
            case 'agregar_proyecto':
                $titulo = $_POST['titulo'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $categoria = $_POST['categoria'] ?? '';
                $destacado = isset($_POST['destacado']) ? 1 : 0;
                $fecha_creacion = date('Y-m-d');
                $imagen = '';
                
                // Procesar imagen si se subió
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'assets/img/proyectos/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array(strtolower($extension), $allowedExtensions)) {
                        $fileName = time() . '_' . $_FILES['imagen']['name'];
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                            $imagen = $uploadPath;
                        }
                    }
                }
                
                if ($titulo && $categoria) {
                    $stmt = $pdo->prepare("INSERT INTO proyectos (titulo, descripcion, categoria, imagen, fecha_creacion, destacado) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$titulo, $descripcion, $categoria, $imagen, $fecha_creacion, $destacado]);
                    $mensaje = 'Proyecto agregado correctamente';
                }
                break;
                
            case 'editar_proyecto':
                $id = $_POST['id'] ?? '';
                $titulo = $_POST['titulo'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $categoria = $_POST['categoria'] ?? '';
                $destacado = isset($_POST['destacado']) ? 1 : 0;
                
                if ($id && $titulo && $categoria) {
                    $stmt = $pdo->prepare("UPDATE proyectos SET titulo = ?, descripcion = ?, categoria = ?, destacado = ? WHERE Id = ?");
                    $stmt->execute([$titulo, $descripcion, $categoria, $destacado, $id]);
                    $mensaje = 'Proyecto actualizado correctamente';
                }
                break;
                
            case 'eliminar_proyecto':
                $id = $_POST['id'] ?? '';
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE proyectos SET activo = 0 WHERE Id = ?");
                    $stmt->execute([$id]);
                    $mensaje = 'Proyecto eliminado correctamente';
                }
                break;
                
            case 'toggle_destacado':
                $id = $_POST['id'] ?? '';
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE proyectos SET destacado = NOT destacado WHERE Id = ?");
                    $stmt->execute([$id]);
                    $mensaje = 'Estado de destacado actualizado';
                }
                break;
        }
    }
}

// Obtener proyectos
$stmt = $pdo->query("SELECT * FROM proyectos WHERE activo = 1 ORDER BY fecha_creacion DESC");
$proyectos = $stmt->fetchAll();

// Obtener estadísticas por categoría
$stmt = $pdo->query("SELECT categoria, COUNT(*) as total FROM proyectos WHERE activo = 1 GROUP BY categoria");
$stats_categoria = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proyectos - EEST14</title>
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
        
        .form-section, .proyectos-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-section h3, .proyectos-section h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, .form-group select, .form-group textarea {
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
        
        .proyectos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .proyecto-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: white;
            position: relative;
        }
        
        .proyecto-card.destacado {
            border-color: #f39c12;
            background: #fef9e7;
        }
        
        .proyecto-imagen {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .proyecto-titulo {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .proyecto-categoria {
            background: #3498db;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .proyecto-descripcion {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        
        .proyecto-acciones {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .badge-destacado {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f39c12;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7em;
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
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
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
                <h1>Gestión de Proyectos</h1>
                <p>Administra los proyectos por categorías</p>
            </div>
            
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <!-- Estadísticas -->
            <div class="stats-grid">
                <?php foreach ($stats_categoria as $stat): ?>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stat['total']; ?></div>
                        <div class="stat-label"><?php echo ucfirst($stat['categoria']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Formulario para agregar proyecto -->
            <div class="form-section">
                <h3>Agregar Nuevo Proyecto</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="agregar_proyecto">
                    <div class="form-grid">
                        <div>
                            <div class="form-group">
                                <label for="titulo">Título del Proyecto:</label>
                                <input type="text" name="titulo" id="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría:</label>
                                <select name="categoria" id="categoria" required>
                                    <option value="">Seleccione categoría</option>
                                    <option value="capacidades">Capacidades</option>
                                    <option value="precapacidades">Pre Capacidades</option>
                                    <option value="saberes">Saberes</option>
                                    <option value="destacados">Destacados</option>
                                    <option value="expo-tecnica">Expotécnica</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea name="descripcion" id="descripcion" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="imagen">Imagen (opcional):</label>
                                <input type="file" name="imagen" id="imagen" accept="image/*">
                                <small>Formatos permitidos: JPG, PNG, GIF</small>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="destacado" id="destacado"> 
                                    Marcar como destacado
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn success">Agregar Proyecto</button>
                </form>
            </div>
            
            <!-- Lista de proyectos -->
            <div class="proyectos-section">
                <h3>Proyectos Existentes</h3>
                <div class="proyectos-grid">
                    <?php foreach ($proyectos as $proyecto): ?>
                        <div class="proyecto-card <?php echo $proyecto['destacado'] ? 'destacado' : ''; ?>">
                            <?php if ($proyecto['destacado']): ?>
                                <div class="badge-destacado">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Destacado
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($proyecto['imagen']): ?>
                                <img src="<?php echo htmlspecialchars($proyecto['imagen']); ?>" 
                                     alt="Imagen del proyecto" class="proyecto-imagen">
                            <?php endif; ?>
                            
                            <div class="proyecto-titulo"><?php echo htmlspecialchars($proyecto['titulo']); ?></div>
                            <div class="proyecto-categoria"><?php echo ucfirst($proyecto['categoria']); ?></div>
                            <div class="proyecto-descripcion">
                                <?php echo htmlspecialchars(substr($proyecto['descripcion'], 0, 100)) . '...'; ?>
                            </div>
                            <div class="proyecto-acciones">
                                <button onclick="editarProyecto(<?php echo $proyecto['Id']; ?>)" class="btn">Editar</button>
                                <button onclick="toggleDestacado(<?php echo $proyecto['Id']; ?>)" class="btn warning">
                                    <?php echo $proyecto['destacado'] ? 'Quitar Destacado' : 'Destacar'; ?>
                                </button>
                                <button onclick="eliminarProyecto(<?php echo $proyecto['Id']; ?>)" class="btn danger">Eliminar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal para editar proyecto -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalEditar()">&times;</span>
            <h3>Editar Proyecto</h3>
            <form id="formEditar" method="POST">
                <input type="hidden" name="accion" value="editar_proyecto">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label for="edit_titulo">Título:</label>
                    <input type="text" name="titulo" id="edit_titulo" required>
                </div>
                <div class="form-group">
                    <label for="edit_categoria">Categoría:</label>
                    <select name="categoria" id="edit_categoria" required>
                        <option value="capacidades">Capacidades</option>
                        <option value="precapacidades">Pre Capacidades</option>
                        <option value="saberes">Saberes</option>
                        <option value="destacados">Destacados</option>
                        <option value="expo-tecnica">Expotécnica</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_descripcion">Descripción:</label>
                    <textarea name="descripcion" id="edit_descripcion" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="destacado" id="edit_destacado"> 
                        Marcar como destacado
                    </label>
                </div>
                <button type="submit" class="btn success">Actualizar</button>
            </form>
        </div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Gestión de Proyectos</p>
                </div>
            </div>
            <div class="footer-separator"></div>
            <div class="footer-bottom">
                <p>© 2024 E.E.S.T. N°14</p>
            </div>
        </div>
    </footer>
    
    <script>
        function editarProyecto(id) {
            // Aquí podrías cargar los datos del proyecto via AJAX
            document.getElementById('edit_id').value = id;
            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModalEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        
        function toggleDestacado(id) {
            if (confirm('¿Cambiar estado de destacado?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="accion" value="toggle_destacado">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function eliminarProyecto(id) {
            if (confirm('¿Está seguro de que desea eliminar este proyecto?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="accion" value="eliminar_proyecto">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalEditar');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
