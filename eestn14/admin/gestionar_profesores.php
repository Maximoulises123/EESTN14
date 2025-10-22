<?php
require_once '../src/config.php';

// Verificar si el usuario es director
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'director') {
    header('Location: ../auth/login.php');
    exit;
}

$error = '';
$success = '';

// Procesar acciones
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'crear_profesor') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $contraseña = $_POST['contraseña'] ?? '';
        
        if ($nombre && $apellido && $email && $contraseña) {
            $stmt = $pdo->prepare("SELECT id FROM profesores WHERE email = ?");
            $stmt->execute([$email]);
            
            if (!$stmt->fetch()) {
                $password_hash = password_hash($contraseña, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO profesores (nombre, apellido, email, telefono, password_hash, fecha_registro) VALUES (?, ?, ?, ?, ?, NOW())");
                if ($stmt->execute([$nombre, $apellido, $email, $telefono, $password_hash])) {
                    $success = 'Profesor creado correctamente';
                } else {
                    $error = 'Error al crear el profesor';
                }
            } else {
                $error = 'El email ya está registrado';
            }
        } else {
            $error = 'Complete todos los campos obligatorios';
        }
    }
    
    if ($action === 'editar_profesor') {
        $id = $_POST['id'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $activo = $_POST['activo'] ?? 0;
        
        if ($id && $nombre && $apellido && $email) {
            $stmt = $pdo->prepare("UPDATE profesores SET nombre = ?, apellido = ?, email = ?, telefono = ?, activo = ? WHERE id = ?");
            if ($stmt->execute([$nombre, $apellido, $email, $telefono, $activo, $id])) {
                $success = 'Profesor actualizado correctamente';
            } else {
                $error = 'Error al actualizar el profesor';
            }
        } else {
            $error = 'Complete todos los campos obligatorios';
        }
    }
    
    if ($action === 'eliminar_profesor') {
        $id = $_POST['id'] ?? '';
        
        if ($id) {
            $stmt = $pdo->prepare("UPDATE profesores SET activo = 0 WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = 'Profesor desactivado correctamente';
            } else {
                $error = 'Error al desactivar el profesor';
            }
        }
    }
}

// Obtener lista de profesores
$stmt = $pdo->query("SELECT * FROM profesores ORDER BY apellido, nombre");
$profesores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Profesores - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gestionar_profesores.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="header">
                <h1>Gestión de Profesores</h1>
                <p>Administra los profesores de la institución</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="admin-controls">
                <button onclick="abrirModalCrear()" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                        <line x1="16" y1="11" x2="22" y2="11"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                    </svg>
                    Crear Nuevo Profesor
                </button>
                <a href="../panels/admin_panel.php" class="btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Volver al Panel
                </a>
            </div>
            
            <div class="busqueda-container">
                <div class="busqueda-wrapper">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="busqueda-icon">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" id="buscarProfesor" placeholder="Buscar profesor por nombre, apellido o email..." class="busqueda-input">
                </div>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaProfesores">
                    <?php foreach ($profesores as $profesor): ?>
                    <tr class="fila-profesor" data-nombre="<?php echo strtolower($profesor['nombre'] . ' ' . $profesor['apellido']); ?>" data-email="<?php echo strtolower($profesor['email']); ?>">
                        <td><?php echo $profesor['id']; ?></td>
                        <td><?php echo htmlspecialchars($profesor['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($profesor['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($profesor['email']); ?></td>
                        <td><?php echo htmlspecialchars($profesor['telefono'] ?? 'No especificado'); ?></td>
                        <td>
                            <span class="<?php echo $profesor['activo'] ? 'status-activo' : 'status-inactivo'; ?>">
                                <?php echo $profesor['activo'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-cell">
                                <button onclick="editarProfesor(<?php echo htmlspecialchars(json_encode($profesor)); ?>)" class="btn btn-edit" title="Editar profesor">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Editar
                                </button>
                                <a href="ver_modelos_profesor.php?profesor_id=<?php echo $profesor['id']; ?>" class="btn btn-success" title="Ver modelos de examen">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14,2 14,8 20,8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                    Modelos
                                </a>
                                <?php if ($profesor['activo']): ?>
                                    <button onclick="eliminarProfesor(<?php echo $profesor['id']; ?>)" class="btn btn-danger" title="Desactivar profesor">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M18 6L6 18M6 6l12 12"/>
                                        </svg>
                                        Desactivar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <!-- Modal para crear profesor -->
    <div id="modalCrear" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalCrear()">&times;</span>
            <h3>Crear Nuevo Profesor</h3>
            <form method="POST">
                <input type="hidden" name="action" value="crear_profesor">
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono (opcional):</label>
                    <input type="text" name="telefono" id="telefono">
                </div>
                
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" name="contraseña" id="contraseña" required>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                        <line x1="16" y1="11" x2="22" y2="11"/>
                        <line x1="19" y1="8" x2="19" y2="14"/>
                    </svg>
                    Crear Profesor
                </button>
            </form>
        </div>
    </div>
    
    <!-- Modal para editar profesor -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalEditar()">&times;</span>
            <h3>Editar Profesor</h3>
            <form method="POST">
                <input type="hidden" name="action" value="editar_profesor">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="form-group">
                    <label for="edit_nombre">Nombre:</label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_apellido">Apellido:</label>
                    <input type="text" name="apellido" id="edit_apellido" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">Email:</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="edit_telefono">
                </div>
                
                <div class="form-group">
                    <label for="edit_activo">Estado:</label>
                    <select name="activo" id="edit_activo">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Actualizar Profesor
                </button>
            </form>
        </div>
    </div>
    
        <?php include '../includes/footer.php'; ?>
    
    <script>
        // Buscador de profesores
        document.getElementById('buscarProfesor').addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            const filas = document.querySelectorAll('.fila-profesor');
            
            filas.forEach(fila => {
                const nombre = fila.getAttribute('data-nombre');
                const email = fila.getAttribute('data-email');
                
                if (nombre.includes(termino) || email.includes(termino)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
        
        function abrirModalCrear() {
            document.getElementById('modalCrear').style.display = 'block';
        }
        
        function cerrarModalCrear() {
            document.getElementById('modalCrear').style.display = 'none';
        }
        
        function editarProfesor(profesor) {
            document.getElementById('edit_id').value = profesor.id;
            document.getElementById('edit_nombre').value = profesor.nombre;
            document.getElementById('edit_apellido').value = profesor.apellido;
            document.getElementById('edit_email').value = profesor.email;
            document.getElementById('edit_telefono').value = profesor.telefono || '';
            document.getElementById('edit_activo').value = profesor.activo;
            document.getElementById('modalEditar').style.display = 'block';
        }
        
        function cerrarModalEditar() {
            document.getElementById('modalEditar').style.display = 'none';
        }
        
        function eliminarProfesor(id) {
            if (confirm('¿Está seguro de que desea desactivar este profesor?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="eliminar_profesor">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modalCrear = document.getElementById('modalCrear');
            const modalEditar = document.getElementById('modalEditar');
            
            if (event.target === modalCrear) {
                modalCrear.style.display = 'none';
            }
            if (event.target === modalEditar) {
                modalEditar.style.display = 'none';
            }
        }
    </script>
</body>
</html>

