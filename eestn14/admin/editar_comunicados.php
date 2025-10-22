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
            case 'eliminar_comunicado':
                $id = $_POST['id'] ?? '';
                if ($id) {
                    $stmt = $pdo->prepare("DELETE FROM comunicados WHERE id = ?");
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
                    $uploadDir = '../assets/img/comunicados/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array(strtolower($extension), $allowedExtensions)) {
                        $fileName = time() . '_' . $_FILES['imagen']['name'];
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                            $imagen = 'assets/img/comunicados/' . $fileName;
                        }
                    }
                }
                
                if ($id && $nombre && $importancia) {
                    if ($imagen) {
                        // Si hay nueva imagen, actualizar con la nueva
                        $stmt = $pdo->prepare("UPDATE comunicados SET titulo = ?, importancia = ?, descripcion = ?, anio = ?, destacado = ?, imagen = ? WHERE id = ?");
                        $stmt->execute([$nombre, $importancia, $descripcion, $año, $destacado, $imagen, $id]);
                    } else {
                        // Si no hay nueva imagen, mantener la actual
                        $stmt = $pdo->prepare("UPDATE comunicados SET titulo = ?, importancia = ?, descripcion = ?, anio = ?, destacado = ? WHERE id = ?");
                        $stmt->execute([$nombre, $importancia, $descripcion, $año, $destacado, $id]);
                    }
                    $mensaje = 'Comunicado actualizado correctamente';
                }
                break;
        }
    }
}

// Obtener comunicados
$stmt = $pdo->query("SELECT * FROM comunicados ORDER BY fecha DESC, id DESC");
$comunicados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comunicados - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/editar_comunicados.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1>Editar y Gestionar Comunicados</h1>
                <p>Administra todos los comunicados de la institución</p>
            </div>
            
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            
            <?php if (empty($comunicados)): ?>
                <div class="empty-state">
                    <h3>No hay comunicados</h3>
                    <p>No hay comunicados registrados en el sistema.</p>
                    <a href="publicar_comunicado.php" class="btn success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Crear Primer Comunicado
                    </a>
                </div>
            <?php else: ?>
                <div class="comunicados-grid">
                    <?php foreach ($comunicados as $comunicado): ?>
                        <div class="comunicado-card <?php echo $comunicado['destacado'] ? 'destacado' : ''; ?>">
                            <?php if ($comunicado['destacado']): ?>
                                <div class="badge-destacado">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg-small">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Destacado
                                </div>
                            <?php endif; ?>
                            
                            <div class="importancia-badge importancia-<?php echo strtolower($comunicado['importancia']); ?>">
                                <?php echo ucfirst($comunicado['importancia']); ?>
                            </div>
                            
                            <div class="comunicado-imagen">
                                <?php if ($comunicado['imagen']): ?>
                                    <?php 
                                    // Construir la ruta completa de la imagen
                                    $rutaImagen = '../assets/img/comunicados/' . basename($comunicado['imagen']);
                                    ?>
                                    <!-- Debug: <?php echo htmlspecialchars($comunicado['imagen']); ?> -> <?php echo htmlspecialchars($rutaImagen); ?> -->
                                    <img src="<?php echo htmlspecialchars($rutaImagen); ?>" 
                                         alt="Imagen del comunicado"
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
                                        <p>Sin imagen</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="comunicado-info">
                                <h3 class="comunicado-titulo"><?php echo htmlspecialchars($comunicado['titulo']); ?></h3>
                                
                                <div class="comunicado-meta">
                                    <div class="meta-item">
                                        <span class="meta-label">Fecha:</span>
                                        <span class="meta-value"><?php echo date('d/m/Y', strtotime($comunicado['fecha'])); ?></span>
                                    </div>
                                    <?php if ($comunicado['numero'] && $comunicado['anio']): ?>
                                        <div class="meta-item">
                                            <span class="meta-label">Comunicado:</span>
                                            <span class="meta-value"><?php echo $comunicado['numero'] . '/' . $comunicado['anio']; ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="meta-item">
                                        <span class="meta-label">ID:</span>
                                        <span class="meta-value"><?php echo $comunicado['id']; ?></span>
                                    </div>
                                </div>
                                
                                <?php if ($comunicado['descripcion']): ?>
                                    <div class="comunicado-descripcion">
                                        <?php echo htmlspecialchars($comunicado['descripcion']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="comunicado-actions">
                                <button onclick="editarComunicado('<?php echo $comunicado['id']; ?>')" class="btn-comunicado btn-editar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Editar
                                </button>
                                <button onclick="eliminarComunicado('<?php echo $comunicado['id']; ?>')" class="btn-comunicado btn-eliminar">
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
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
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
                    <button type="button" onclick="cerrarModalEditar()" class="btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Cancelar
                    </button>
                    <button type="submit" class="btn success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
        <?php include '../includes/footer.php'; ?>
    
    <script>
        // Datos de los comunicados para JavaScript
        const comunicados = <?php echo json_encode($comunicados); ?>;
        
        function editarComunicado(id) {
            const comunicado = comunicados.find(c => c.id == id);
            if (!comunicado) return;
            
            document.getElementById('edit_id').value = comunicado.id;
            document.getElementById('edit_nombre').value = comunicado.titulo;
            document.getElementById('edit_importancia').value = comunicado.importancia;
            document.getElementById('edit_año').value = comunicado.anio || new Date().getFullYear();
            document.getElementById('edit_descripcion').value = comunicado.descripcion || '';
            document.getElementById('edit_destacado').checked = comunicado.destacado == 1;
            
            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModalEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        
        function eliminarComunicado(id) {
            const comunicado = comunicados.find(c => c.id == id);
            if (!comunicado) return;
            
            if (confirm(`¿Está seguro de que desea eliminar el comunicado "${comunicado.titulo}"?\n\nEsta acción no se puede deshacer.`)) {
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
