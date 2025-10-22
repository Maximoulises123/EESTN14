<?php
require_once '../src/config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo'] ?? 'usuario';

// Obtener datos del usuario según su tipo
$usuario = null;
$nombre = '';
$email = '';

if ($tipo_usuario === 'director') {
    $stmt = $pdo->prepare("SELECT * FROM directivos WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();
    $nombre = $usuario['nombre'] ?? '';
    $email = $usuario['email'] ?? '';
} elseif ($tipo_usuario === 'profesor') {
    $stmt = $pdo->prepare("SELECT * FROM profesores WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();
    $nombre = ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? '');
    $email = $usuario['email'] ?? '';
} elseif ($tipo_usuario === 'alumno') {
    $stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();
    $nombre = ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? '');
    $email = $usuario['email'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/perfil.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="perfil-container">
            <!-- Panel lateral izquierdo -->
            <div class="perfil-sidebar">
                <div class="avatar-container">
                    <div class="avatar">
                        <?php 
                        $iniciales = '';
                        if ($tipo_usuario === 'alumno' && $usuario) {
                            $iniciales = substr($usuario['nombre'] ?? '', 0, 1) . substr($usuario['apellido'] ?? '', 0, 1);
                        } elseif ($tipo_usuario === 'profesor' && $usuario) {
                            $iniciales = substr($usuario['nombre'] ?? '', 0, 1) . substr($usuario['apellido'] ?? '', 0, 1);
                        } else {
                            $iniciales = substr($nombre, 0, 2);
                        }
                        echo strtoupper($iniciales);
                        ?>
                    </div>
                    <div class="camera-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </div>
                </div>
                
                <h2 class="nombre-usuario"><?php echo htmlspecialchars($nombre); ?></h2>
                
                <?php if ($tipo_usuario === 'alumno' && $usuario): ?>
                    <p class="dni-usuario">DNI: <?php echo htmlspecialchars($usuario['dni']); ?></p>
                <?php endif; ?>
                
                <div class="tipo-usuario-badge">
                    <?php 
                    switch($tipo_usuario) {
                        case 'director': echo 'Director'; break;
                        case 'profesor': echo 'Profesor'; break;
                        case 'alumno': echo 'Estudiante'; break;
                        default: echo 'Usuario'; break;
                    }
                    ?>
                </div>
                
                <div class="info-rapida">
                    <div class="info-item-rapida">
                        <span class="icono">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <span>Email verificado</span>
                    </div>
                    
                    <?php if ($tipo_usuario === 'alumno' && $usuario): ?>
                        <div class="info-item-rapida">
                            <span class="icono">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                </svg>
                            </span>
                            <span><?php echo htmlspecialchars($usuario['especialidad'] ?? 'Informática'); ?></span>
                        </div>
                        <div class="info-item-rapida">
                            <span class="icono">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </span>
                            <span>Año <?php echo $usuario['anio']; ?></span>
                        </div>
                    <?php elseif ($tipo_usuario === 'profesor' && $usuario): ?>
                        <div class="info-item-rapida">
                            <span class="icono">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <span>Profesor Activo</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <a href="../auth/logout.php" class="btn-cerrar-sesion">Cerrar Sesión</a>
            </div>
            
            <!-- Panel principal derecho -->
            <div class="perfil-main">
                <div class="perfil-tabs">
                    <button class="tab-btn active" data-tab="perfil">Perfil</button>
                    <button class="tab-btn" data-tab="academico">Académico</button>
                    <button class="tab-btn" data-tab="configuracion">Configuración</button>
                </div>
                
                <!-- Tab Perfil -->
                <div class="tab-content active" id="perfil">
                    <div class="section-header">
                        <h3>Información Personal</h3>
                        <button class="btn-editar">Editar</button>
                    </div>
                    
                    <div class="form-group">
                        <label>Nombre completo</label>
                        <input type="text" value="<?php echo htmlspecialchars($nombre); ?>" readonly>
                    </div>
                    
                    <?php if ($tipo_usuario === 'alumno' && $usuario): ?>
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" value="<?php echo htmlspecialchars($usuario['dni']); ?>" readonly>
                            <small>El DNI no se puede modificar por seguridad</small>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Email <?php echo $tipo_usuario === 'alumno' ? '(opcional)' : ''; ?></label>
                        <input type="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    </div>
                </div>
                
                <!-- Tab Académico -->
                <div class="tab-content" id="academico">
                    <div class="section-header">
                        <h3>Información Académica</h3>
                    </div>
                    
                    <?php if ($tipo_usuario === 'alumno' && $usuario): ?>
                        <div class="form-group">
                            <label>Año</label>
                            <input type="text" value="<?php echo $usuario['anio']; ?>° Año" readonly>
                        </div>
                        
                        <?php if ($usuario['division']): ?>
                        <div class="form-group">
                            <label>División</label>
                            <input type="text" value="<?php echo htmlspecialchars($usuario['division']); ?>" readonly>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" value="<?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>" readonly>
                        </div>
                    <?php elseif ($tipo_usuario === 'profesor' && $usuario): ?>
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" value="<?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>" readonly>
                        </div>
                        
                        <?php if ($usuario['telefono']): ?>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" readonly>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Tab Configuración -->
                <div class="tab-content" id="configuracion">
                    <div class="section-header">
                        <h3>Accesos Rápidos</h3>
                    </div>
                    
                    <div class="accesos-grid">
                        <?php if ($tipo_usuario === 'director'): ?>
                            <a href="admin_panel.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                                Panel de Administración
                            </a>
                            <a href="../admin/publicar_comunicado.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                Publicar Comunicado
                            </a>
                            <a href="../admin/gestionar_profesores.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Gestionar Profesores
                            </a>
                            <a href="../admin/gestionar_preinscripciones.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                                Gestionar Preinscripciones
                            </a>
                        <?php elseif ($tipo_usuario === 'profesor'): ?>
                            <a href="profesor_panel.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                </svg>
                                Mi Panel
                            </a>
                            <a href="../modelos/subir_modelo_examen.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7,10 12,15 17,10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Subir Modelo de Examen
                            </a>
                            <a href="../modelos/modelos_examen.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                                Ver Mis Modelos
                            </a>
                        <?php elseif ($tipo_usuario === 'alumno'): ?>
                            <a href="alumno_panel.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                </svg>
                                Mis Materias
                            </a>
                            <a href="../modelos/modelos_examen.php" class="btn-acceso">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                                Modelos de Examen
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
    
    <script>
        // Funcionalidad de pestañas
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetTab = btn.getAttribute('data-tab');
                    
                    // Remover clase active de todos los botones y contenidos
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Agregar clase active al botón clickeado y su contenido
                    btn.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>

