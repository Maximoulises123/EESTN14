<?php
require_once '../src/config.php';

$mensaje = '';
$error = '';

if ($_POST) {
    $nombre = trim($_POST['nombre'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    
    if (empty($nombre) || empty($dni)) {
        $error = 'Por favor complete todos los campos obligatorios';
    } elseif (!is_numeric($dni) || strlen($dni) < 7 || strlen($dni) > 8) {
        $error = 'El DNI debe ser un número de 7 u 8 dígitos';
    } else {
        // Verificar si ya existe una pre-inscripción con este DNI
        $stmt = $pdo->prepare("SELECT * FROM preinsicripcion WHERE DNI = ?");
        $stmt->execute([$dni]);
        $existente = $stmt->fetch();
        
        if ($existente) {
            $error = 'Ya existe una pre-inscripción con este DNI';
        } else {
            // Insertar nueva pre-inscripción
            $stmt = $pdo->prepare("INSERT INTO preinsicripcion (Nombre, DNI, estado, inscripto) VALUES (?, ?, 'pendiente', 0)");
            $stmt->execute([$nombre, $dni]);
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
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/preinscripcion.css">
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
                    <li class="dropdown">
                        <span class="dropdown-trigger">Nosotros</span>
                        <ul class="dropdown-menu">
                            <li><a href="index.php#historia">Historia</a></li>
                            <li><a href="index.php#proposito">Propósito</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <span class="dropdown-trigger">Tecnicatura</span>
                        <ul class="dropdown-menu">
                            <li><a href="index.php#alimentos">Alimentos</a></li>
                            <li><a href="index.php#informatica">Informática</a></li>
                            <li><a href="index.php#programacion">Programación</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <span class="dropdown-trigger">Proyectos</span>
                        <ul class="dropdown-menu">
                            <li><a href="index.php#expo-tecnica">Expotécnica</a></li>
                            <li><a href="index.php#saberes">Saberes</a></li>
                            <li><a href="index.php#pre-capacidades">Pre Capacidades</a></li>
                            <li><a href="index.php#capacidades">Capacidades</a></li>
                            <li><a href="index.php#destacados">Destacados</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <span class="dropdown-trigger">Ciclos</span>
                        <ul class="dropdown-menu">
                            <li><a href="ciclo_basico.php">Ciclo Básico</a></li>
                            <li><a href="ciclo_superior.php">Ciclo Superior</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="comunicados.php">Comunicados</a></li>
                    <li><a href="index.php#contactos">Contactos</a></li>
                    <li><a href="preinscripcion.php">Preinscripciones</a></li>
                </ul>
            </nav>
            
            <div class="user-actions">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="perfil.php" class="btn-perfil">Perfil</a>
                <?php else: ?>
                    <a href="registro.php" class="btn-registro">Registrarse</a>
                    <a href="login.php" class="btn-login">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Pre-inscripciones 2025</h1>
                <p class="hero-subtitle">Tu futuro técnico comienza aquí</p>
            </div>
        </section>
        
        <!-- Info Section -->
        <section class="info-hero">
            <div class="info-hero-content">
                <h2 class="info-hero-title">Proceso de Inscripción</h2>
                <p class="info-hero-text">Bienvenidos al proceso de inscripción para el ciclo lectivo 2025. Te acompañamos paso a paso para que puedas formar parte de nuestra comunidad educativa y comenzar tu formación técnica profesional.</p>
            </div>
        </section>
        
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
                        <h3 class="info-title">Documentos Requeridos</h3>
                        <ul class="document-list">
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                Certificado de estudios primarios completos
                            </li>
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                Partida de nacimiento original
                            </li>
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                DNI del estudiante y responsables (fotocopia)
                            </li>
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                Certificado de vacunación completo
                            </li>
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                Ficha médica actualizada
                            </li>
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                2 fotos 4x4 del estudiante
                            </li>
                            <li class="document-item">
                                <span class="check-icon">✓</span>
                                Formulario de inscripción completo
                            </li>
                        </ul>
                    </div>
                    
                    <div class="important-notice">
                        <h4 class="notice-title">Importante</h4>
                        <p class="notice-text">
                            Toda la documentación debe estar actualizada y en regla. Las fotocopias deben estar acompañadas de sus respectivos originales para cotejo.
                        </p>
                    </div>
                </div>
                
                <!-- Columna derecha - Formulario -->
                <div class="right-column">
                    <div class="form-container">
                        <h2>Formulario de Pre-inscripción</h2>
                        <form method="POST" id="preinscripcionForm">
                            <div class="form-group">
                                <label for="nombre">Nombre y Apellido<span class="required">*</span></label>
                                <input type="text" name="nombre" id="nombre" required 
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                                       placeholder="Ingrese su nombre completo">
                            </div>
                            
                            <div class="form-group">
                                <label for="dni">DNI<span class="required">*</span></label>
                                <input type="number" name="dni" id="dni" required 
                                       value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>"
                                       placeholder="Ingrese su DNI sin puntos">
                            </div>
                            
                            <button type="submit" class="btn-submit">Enviar Pre-inscripción</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sección de contacto -->
            <div class="contact-section">
                <h3>¿Necesitas más información?</h3>
                <div class="contact-grid">
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <h4>Teléfono</h4>
                        <p>02202 42-8307</p>
                        <p class="hours">Lunes a viernes 8:00-17:00</p>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">✉️</div>
                        <h4>Email</h4>
                        <p>eest14platenza@abc.gob.ar</p>
                        <p class="hours">Respuesta en 24-48 horas</p>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">👤</div>
                        <h4>Atención Personal</h4>
                        <p>Secretaría</p>
                        <p class="hours">Lunes a viernes 8:00-17:00</p>
                    </div>
                </div>
            </div>
            
        </div>
    </main>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Formando técnicos competentes y ciudadanos responsables desde 1980. Excelencia educativa para el futuro de Argentina.</p>
                    <div class="contact-info">
                        <div class="contact-item">Bariloche 6615, B1758 González Catán, Provincia de Buenos Aires</div>
                        <div class="contact-item"><a href="tel:02202428307">02202 42-8307</a></div>
                        <div class="contact-item"><a href="mailto:eest14platenza@abc.gob.ar">eest14platenza@abc.gob.ar</a></div>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Educación</h3>
                    <ul>
                        <li><a href="#ciclo-basico">Ciclo Básico</a></li>
                        <li><a href="#ciclo-superior">Ciclo Superior</a></li>
                        <li><a href="#tecnologia-alimentos">Tecnología en Alimentos</a></li>
                        <li><a href="#informatica">Informática</a></li>
                        <li><a href="#programacion">Programación</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="#historia">Historia</a></li>
                        <li><a href="#proposito">Propósito</a></li>
                        <li><a href="comunicados.php">Comunicados</a></li>
                        <li><a href="#proyectos">Proyectos</a></li>
                        <li><a href="preinscripcion.php">Inscripciones</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d498.53141687234967!2d-58.61777885824825!3d-34.7831965766796!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc5b3899394c5%3A0x5e91bad5b50da11d!2sEEST%20N14!5e1!3m2!1ses!2sar!4v1757653371479!5m2!1ses!2sar" 
                            width="100%" 
                            height="200" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
            
            <div class="footer-separator"></div>
            <div class="footer-bottom">
                <p>© 2024 E.E.S.T. N°14</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Validación del formulario
        document.getElementById('preinscripcionForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const dni = document.getElementById('dni').value.trim();
            
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
