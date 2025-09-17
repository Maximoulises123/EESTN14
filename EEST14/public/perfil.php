<?php
require_once '../src/config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo'] ?? 'usuario';

// Obtener datos del usuario (todos están en la tabla usuarios)
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE Id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();
$nombre = $usuario['nombre'] ?? '';
$email = $usuario['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - EEST14</title>
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
        <div class="perfil-container">
        <h2>Mi Perfil</h2>
        
        <div class="info-usuario">
            <div class="tipo-usuario <?php echo $tipo_usuario === 'admin' ? 'tipo-admin' : ''; ?>">
                <?php echo $tipo_usuario === 'admin' ? 'Administrador' : 'Usuario'; ?>
            </div>
            
            <div class="info-item">
                <span class="info-label">Nombre:</span>
                <span class="info-value"><?php echo htmlspecialchars($nombre); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($email); ?></span>
            </div>
            
            <?php if ($tipo_usuario === 'usuario'): ?>
            <div class="info-item">
                <span class="info-label">Fecha de registro:</span>
                <span class="info-value"><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <a href="logout.php" class="btn-cerrar-sesion">Cerrar Sesión</a>
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
