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
            case 'eliminar_comunicado':
                $id = $_POST['id'] ?? '';
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM comunicado WHERE Id = ?");
                    $stmt->execute([$id]);
                    $mensaje = 'Comunicado eliminado correctamente';
                }
                break;
                
            case 'editar_comunicado':
                $id = $_POST['id'] ?? '';
                $nombre = $_POST['nombre'] ?? '';
                $importancia = $_POST['importancia'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $año = $_POST['año'] ?? '';
                $destacado = isset($_POST['destacado']) ? 1 : 0;
                $imagen = '';
                
                // Procesar nueva imagen si se subió
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'assets/img/comunicados/';
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
                
                if ($id && $nombre && $importancia) {
                    if ($imagen) {
                        // Si hay nueva imagen, actualizar con la nueva
                        $stmt = $pdo->prepare("UPDATE comunicado SET Nombre = ?, Importancia = ?, descripcion = ?, año = ?, destacado = ?, imagen = ? WHERE Id = ?");
                        $stmt->execute([$nombre, $importancia, $descripcion, $año, $destacado, $imagen, $id]);
                    } else {
                        // Si no hay nueva imagen, mantener la actual
                        $stmt = $pdo->prepare("UPDATE comunicado SET Nombre = ?, Importancia = ?, descripcion = ?, año = ?, destacado = ? WHERE Id = ?");
                        $stmt->execute([$nombre, $importancia, $descripcion, $año, $destacado, $id]);
                    }
                    $mensaje = 'Comunicado actualizado correctamente';
                }
                break;
        }
    }
}

// Obtener comunicados
$stmt = $pdo->query("SELECT * FROM comunicado ORDER BY Fecha DESC, Id DESC");
$comunicados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comunicados - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/admin.css">
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
        
        .comunicados-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .comunicado-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
            position: relative;
        }
        
        .comunicado-card.destacado {
            border-left-color: #f39c12;
            background: #fef9e7;
        }
        
        .comunicado-imagen {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .comunicado-titulo {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .comunicado-meta {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        
        .comunicado-descripcion {
            color: #34495e;
            font-size: 0.9em;
            line-height: 1.4;
            margin-bottom: 20px;
            max-height: 60px;
            overflow: hidden;
        }
        
        .comunicado-acciones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #2980b9;
            transform: translateY(-1px);
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
        
        .badge-destacado {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f39c12;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7em;
            font-weight: bold;
        }
        
        .importancia-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .importancia-urgente {
            background: #e74c3c;
            color: white;
        }
        
        .importancia-normal {
            background: #f39c12;
            color: white;
        }
        
        .importancia-informativo {
            background: #3498db;
            color: white;
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
            max-height: 80vh;
            overflow-y: auto;
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
            box-sizing: border-box;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .empty-state h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #95a5a6;
        }
        
        .empty-state p {
            font-size: 1.1em;
            line-height: 1.6;
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
                <h1>Editar y Gestionar Comunicados</h1>
                <p>Administra todos los comunicados de la institución</p>
            </div>
            
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <div style="text-align: center; margin-bottom: 30px;">
                <a href="publicar_comunicado.php" class="btn success">+ Nuevo Comunicado</a>
                <a href="admin_panel.php" class="btn">← Volver al Panel</a>
            </div>
            
            <?php if (empty($comunicados)): ?>
                <div class="empty-state">
                    <h3>No hay comunicados</h3>
                    <p>No hay comunicados registrados en el sistema.</p>
                    <a href="publicar_comunicado.php" class="btn success">Crear Primer Comunicado</a>
                </div>
            <?php else: ?>
                <div class="comunicados-grid">
                    <?php foreach ($comunicados as $comunicado): ?>
                        <div class="comunicado-card <?php echo $comunicado['destacado'] ? 'destacado' : ''; ?>">
                            <?php if ($comunicado['destacado']): ?>
                                <div class="badge-destacado">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Destacado
                                </div>
                            <?php endif; ?>
                            
                            <div class="importancia-badge importancia-<?php echo $comunicado['Importancia']; ?>">
                                <?php echo ucfirst($comunicado['Importancia']); ?>
                            </div>
                            
                            <?php if ($comunicado['imagen']): ?>
                                <img src="<?php echo htmlspecialchars($comunicado['imagen']); ?>" 
                                     alt="Imagen del comunicado" 
                                     class="comunicado-imagen">
                            <?php else: ?>
                                <img src="Logo14.jpg" alt="Logo EEST14" class="comunicado-imagen">
                            <?php endif; ?>
                            
                            <div class="comunicado-titulo"><?php echo htmlspecialchars($comunicado['Nombre']); ?></div>
                            
                            <div class="comunicado-meta">
                                <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($comunicado['Fecha'])); ?><br>
                                <?php if ($comunicado['numero_comunicado'] && $comunicado['año']): ?>
                                    <strong>Comunicado:</strong> <?php echo $comunicado['numero_comunicado'] . '/' . $comunicado['año']; ?><br>
                                <?php endif; ?>
                                <strong>ID:</strong> <?php echo $comunicado['Id']; ?>
                            </div>
                            
                            <?php if ($comunicado['descripcion']): ?>
                                <div class="comunicado-descripcion">
                                    <?php echo htmlspecialchars($comunicado['descripcion']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="comunicado-acciones">
                                <button onclick="editarComunicado(<?php echo $comunicado['Id']; ?>)" class="btn">Editar</button>
                                <button onclick="eliminarComunicado(<?php echo $comunicado['Id']; ?>)" class="btn danger">Eliminar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Modal para editar comunicado -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalEditar()">&times;</span>
            <h3>Editar Comunicado</h3>
            <form id="formEditar" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="editar_comunicado">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_nombre">Título del Comunicado:</label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_importancia">Importancia:</label>
                    <select name="importancia" id="edit_importancia" required>
                        <option value="urgente">Urgente</option>
                        <option value="normal">Normal</option>
                        <option value="informativo">Informativo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_año">Año:</label>
                    <input type="number" name="año" id="edit_año" min="2020" max="2030" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_descripcion">Descripción:</label>
                    <textarea name="descripcion" id="edit_descripcion"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_imagen">Nueva Imagen (opcional):</label>
                    <input type="file" name="imagen" id="edit_imagen" accept="image/*">
                    <small>Dejar vacío para mantener la imagen actual</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="destacado" id="edit_destacado"> 
                        Marcar como destacado
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="cerrarModalEditar()" class="btn">Cancelar</button>
                    <button type="submit" class="btn success">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Gestión de Comunicados</p>
                </div>
            </div>
            <div class="footer-separator"></div>
            <div class="footer-bottom">
                <p>© 2024 E.E.S.T. N°14</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Datos de los comunicados para JavaScript
        const comunicados = <?php echo json_encode($comunicados); ?>;
        
        function editarComunicado(id) {
            const comunicado = comunicados.find(c => c.Id == id);
            if (!comunicado) return;
            
            document.getElementById('edit_id').value = comunicado.Id;
            document.getElementById('edit_nombre').value = comunicado.Nombre;
            document.getElementById('edit_importancia').value = comunicado.Importancia;
            document.getElementById('edit_año').value = comunicado.año || new Date().getFullYear();
            document.getElementById('edit_descripcion').value = comunicado.descripcion || '';
            document.getElementById('edit_destacado').checked = comunicado.destacado == 1;
            
            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModalEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        
        function eliminarComunicado(id) {
            const comunicado = comunicados.find(c => c.Id == id);
            if (!comunicado) return;
            
            if (confirm(`¿Está seguro de que desea eliminar el comunicado "${comunicado.Nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="accion" value="eliminar_comunicado">
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
