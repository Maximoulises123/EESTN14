<?php
require_once '../src/config.php';

$mensaje = '';
$error = '';

if ($_POST) {
    $nombre = trim($_POST['nombre'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    
    if (empty($nombre) || empty($dni) || empty($contacto)) {
        $error = 'Por favor complete todos los campos obligatorios';
    } elseif (!is_numeric($dni) || strlen($dni) < 7 || strlen($dni) > 8) {
        $error = 'El DNI debe ser un número de 7 u 8 dígitos';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM preinscripciones WHERE dni = ?");
        $stmt->execute([$dni]);
        $existente = $stmt->fetch();
        
        if ($existente) {
            $error = 'Ya existe una pre-inscripción con este DNI';
        } else {
            $stmt = $pdo->prepare("INSERT INTO preinscripciones (nombre, dni, contacto, estado, inscripto) VALUES (?, ?, ?, 'pendiente', 0)");
            $stmt->execute([$nombre, $dni, $contacto]);
            $mensaje = 'Pre-inscripción enviada correctamente. Nos pondremos en contacto con usted.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-inscripciones - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/preinscripcion.css">
</head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="main-content">
            
            <?php if ($mensaje): ?>
                <div class="mensaje success"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="mensaje error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Layout de dos columnas -->
            <div class="two-column-layout">
                <!-- Columna izquierda - Información -->
                <div class="left-column">
                    <div class="info-section">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                            Documentos Requeridos
                        </h3>
                        <ul class="document-list">
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                Certificado de estudios primarios completos
                            </li>
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                Partida de nacimiento original
                            </li>
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                DNI del estudiante y responsables (fotocopia)
                            </li>
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                Certificado de vacunación completo
                            </li>
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                Ficha médica actualizada
                            </li>
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                2 fotos 4x4 del estudiante
                            </li>
                            <li class="document-item">
                                <span class="check-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20,6 9,17 4,12"/>
                                    </svg>
                                </span>
                                Formulario de inscripción completo
                            </li>
                        </ul>
                    </div>
                    
                    <div class="important-notice">
                        <h4 class="notice-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            Importante
                        </h4>
                        <p class="notice-text">
                            Toda la documentación debe estar actualizada y en regla. Las fotocopias deben estar acompañadas de sus respectivos originales para cotejo.
                        </p>
                    </div>
                </div>
                
                <!-- Columna derecha - Formulario -->
                <div class="right-column">
                    <div class="form-container">
                        <h2>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                <path d="M12 11h4"/>
                                <path d="M12 16h4"/>
                                <path d="M8 11h.01"/>
                                <path d="M8 16h.01"/>
                            </svg>
                            Formulario de Pre-inscripción
                        </h2>
                        <form method="POST" id="preinscripcionForm" novalidate>
                            <div class="form-group">
                                <label for="nombre">Nombre y Apellido</label>
                                <input type="text" name="nombre" id="nombre" required 
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                                       placeholder="Ingrese su nombre completo">
                            </div>
                            
                            <div class="form-group">
                                <label for="dni">DNI</label>
                                <input type="number" name="dni" id="dni" required 
                                       value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>"
                                       placeholder="Ingrese su DNI sin puntos">
                            </div>
                            
                            <div class="form-group">
                                <label for="contacto">Email o Teléfono</label>
                                <input type="text" name="contacto" id="contacto" required 
                                       value="<?php echo htmlspecialchars($_POST['contacto'] ?? ''); ?>"
                                       placeholder="Ingrese un email o teléfono de contacto">
                            </div>
                            
                            <button type="submit" class="btn-submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                Enviar Pre-inscripción
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            
            
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
    
    <script>
        // Validación del formulario y del campo de contacto (email o teléfono)
        document.getElementById('preinscripcionForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const dni = document.getElementById('dni').value.trim();
            const contacto = document.getElementById('contacto').value.trim();
            
            // Validar nombre
            if (nombre.length < 2) {
                e.preventDefault();
                alert('El nombre debe tener al menos 2 caracteres');
                document.getElementById('nombre').focus();
                return;
            }
            
            // Validar DNI
            if (!dni || dni.length < 7 || dni.length > 8) {
                e.preventDefault();
                alert('El DNI debe tener entre 7 y 8 dígitos');
                document.getElementById('dni').focus();
                return;
            }
            
            // Validar contacto (email o teléfono)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^\d{8,15}$/;
            if (!emailRegex.test(contacto) && !phoneRegex.test(contacto)) {
                e.preventDefault();
                alert('Ingrese un email válido o un teléfono (8-15 dígitos)');
                document.getElementById('contacto').focus();
                return;
            }
            
            // Mostrar mensaje de confirmación
            if (confirm('¿Está seguro de que desea enviar la pre-inscripción?')) {
                // El formulario se enviará normalmente
            } else {
                e.preventDefault();
            }
        });
        
        // Validación en tiempo real del DNI
        document.getElementById('dni').addEventListener('input', function(e) {
            let value = e.target.value;
            // Solo permitir números
            value = value.replace(/[^0-9]/g, '');
            e.target.value = value;
        });
        
        // Normalización en tiempo real del contacto (si es teléfono, solo números)
        document.getElementById('contacto').addEventListener('input', function(e) {
            let value = e.target.value;
            // Si no contiene '@', tratamos como teléfono y forzamos solo números
            if (value.indexOf('@') === -1) {
                value = value.replace(/[^0-9]/g, '');
            }
            e.target.value = value;
        });
        
        // Validación en tiempo real del nombre
        document.getElementById('nombre').addEventListener('input', function(e) {
            let value = e.target.value;
            // Permitir letras, espacios y algunos caracteres especiales
            value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            e.target.value = value;
        });
    </script>
</body>
</html>
