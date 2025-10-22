<?php
require_once '../src/config.php';

// Verificar si está logueado y es administrador o director
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'director')) {
    header('Location: ../auth/login.php');
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
                    $uploadDir = '../assets/img/proyectos/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array(strtolower($extension), $allowedExtensions)) {
                        $fileName = time() . '_' . $_FILES['imagen']['name'];
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                            $imagen = 'assets/img/proyectos/' . $fileName;
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
                    // Obtener la imagen actual
                    $stmt = $pdo->prepare("SELECT imagen FROM proyectos WHERE id = ?");
                    $stmt->execute([$id]);
                    $proyecto_actual = $stmt->fetch();
                    $imagen = $proyecto_actual['imagen'];
                    
                    // Procesar nueva imagen si se subió
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = '../assets/img/proyectos/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        
                        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (in_array(strtolower($extension), $allowedExtensions)) {
                            $fileName = time() . '_' . $_FILES['imagen']['name'];
                            $uploadPath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                                // Eliminar imagen anterior si existe
                                if ($imagen && file_exists('../' . $imagen)) {
                                    unlink('../' . $imagen);
                                }
                                $imagen = 'assets/img/proyectos/' . $fileName;
                            }
                        }
                    }
                    
                    $stmt = $pdo->prepare("UPDATE proyectos SET titulo = ?, descripcion = ?, categoria = ?, destacado = ?, imagen = ? WHERE id = ?");
                    $stmt->execute([$titulo, $descripcion, $categoria, $destacado, $imagen, $id]);
                    $mensaje = 'Proyecto actualizado correctamente';
                }
                break;
                
            case 'eliminar_proyecto':
                $id = $_POST['id'] ?? '';
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE proyectos SET activo = 0 WHERE id = ?");
                    $stmt->execute([$id]);
                    $mensaje = 'Proyecto eliminado correctamente';
                }
                break;
                
            case 'toggle_destacado':
                $id = $_POST['id'] ?? '';
                if ($id) {
                    $stmt = $pdo->prepare("UPDATE proyectos SET destacado = NOT destacado WHERE id = ?");
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
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gestionar_proyectos.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <!-- Banner principal -->
        <section class="hero-banner">
            <div class="hero-content">
                <h1>Proyectos 2025</h1>
                <p>Innovación y creatividad técnica</p>
            </div>
        </section>
        
        <div class="admin-container">
            <div class="admin-header">
                <h2>Gestión de Proyectos</h2>
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
                    <button type="submit" class="btn success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Agregar Proyecto
                    </button>
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
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg-small">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Destacado
                                </div>
                            <?php endif; ?>
                            
                            <div class="proyecto-imagen-container">
                                <?php if ($proyecto['imagen']): ?>
                                    <?php 
                                    // Construir la ruta completa de la imagen
                                    $rutaImagen = '../' . $proyecto['imagen'];
                                    ?>
                                    <!-- Debug: <?php echo htmlspecialchars($proyecto['imagen']); ?> -> <?php echo htmlspecialchars($rutaImagen); ?> -->
                                    <img src="<?php echo htmlspecialchars($rutaImagen); ?>" 
                                         alt="Imagen del proyecto" 
                                         class="proyecto-imagen"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="imagen-placeholder" style="display: none;">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21,15 16,10 5,21"/>
                                        </svg>
                                        <p>Imagen no encontrada</p>
                                    </div>
                                <?php else: ?>
                                    <div class="imagen-placeholder">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21,15 16,10 5,21"/>
                                        </svg>
                                        <p>Imagen del proyecto</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="proyecto-titulo"><?php echo htmlspecialchars($proyecto['titulo']); ?></div>
                            <div class="proyecto-categoria"><?php echo ucfirst($proyecto['categoria']); ?></div>
                            <div class="proyecto-descripcion">
                                <?php echo htmlspecialchars(substr($proyecto['descripcion'], 0, 100)) . '...'; ?>
                            </div>
                            <div class="proyecto-acciones">
                                <button onclick="editarProyecto(<?php echo $proyecto['id']; ?>)" class="btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Editar
                                </button>
                                <button onclick="toggleDestacado(<?php echo $proyecto['id']; ?>)" class="btn warning">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <?php echo $proyecto['destacado'] ? 'Quitar Destacado' : 'Destacar'; ?>
                                </button>
                                <button onclick="eliminarProyecto(<?php echo $proyecto['id']; ?>)" class="btn danger">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3,6 5,6 21,6"/>
                                        <path d="M19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                    </svg>
                                    Eliminar
                                </button>
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
                <button type="submit" class="btn success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    Actualizar
                </button>
            </form>
        </div>
    </div>
    
        <?php include '../includes/footer.php'; ?>
    
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

