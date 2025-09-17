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
                            <li><a href="#historia">Historia</a></li>
                            <li><a href="#proposito">Propósito</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <span class="dropdown-trigger">Tecnicatura</span>
                        <ul class="dropdown-menu">
                            <li><a href="#alimentos">Alimentos</a></li>
                            <li><a href="#informatica">Informática</a></li>
                            <li><a href="#programacion">Programación</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <span class="dropdown-trigger">Proyectos</span>
                        <ul class="dropdown-menu">
                            <li><a href="#expo-tecnica">Expotécnica</a></li>
                            <li><a href="#saberes">Saberes</a></li>
                            <li><a href="#pre-capacidades">Pre Capacidades</a></li>
                            <li><a href="#capacidades">Capacidades</a></li>
                            <li><a href="#destacados">Destacados</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <span class="dropdown-trigger">Ciclos</span>
                        <ul class="dropdown-menu">
                            <li><a href="#ciclo-basico">Ciclo Básico</a></li>
                            <li><a href="#ciclo-superior">Ciclo Superior</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="comunicados.php">Comunicados</a></li>
                    <li><a href="#contactos">Contactos</a></li>
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
        <div class="main-content">
            
            <?php if ($mensaje): ?>
                <div class="mensaje success"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="mensaje error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre y Apellido<span class="required"></span></label>
                        <input type="text" name="nombre" id="nombre" required 
                               value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                               placeholder="Ingrese su nombre completo">
                    </div>
                    
                    <div class="form-group">
                        <label for="dni">DNI<span class="required"></span></label>
                        <input type="number" name="dni" id="dni" required 
                               value="<?php echo htmlspecialchars($_POST['dni'] ?? ''); ?>"
                               placeholder="Ingrese su DNI sin puntos">
                    </div>
                    
                    <button type="submit" class="btn-submit">Enviar Pre-inscripción</button>
                </form>
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
</body>
</html>
