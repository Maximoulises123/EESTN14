<?php
require_once '../src/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclo Básico - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/ciclo_basico.css">
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
                            <li><a href="ciclo_basico.php">Ciclo Básico</a></li>
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
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Ciclo Básico</h1>
                <p class="hero-subtitle">Fundamentos técnicos para todos los estudiantes</p>
            </div>
        </section>
        
        <!-- Info Section -->
        <section class="info-hero">
            <div class="info-hero-content">
                <h2 class="info-hero-title">Primer Ciclo de Formación Técnica</h2>
                <p class="info-hero-text">El ciclo básico comprende los primeros tres años de la educación secundaria técnica, donde los estudiantes adquieren los conocimientos fundamentales que les permitirán especializarse en las diferentes modalidades técnicas.</p>
            </div>
        </section>
        
        <div class="main-content">
            
            <div class="ciclos-container">
                <!-- 1° AÑO -->
                <div class="ciclo-card">
                    <h2 class="ciclo-title">1° AÑO</h2>
                    <ul class="materias-list">
                        <li class="materia-item">Educación Artística Plástica</li>
                        <li class="materia-item">Inglés</li>
                        <li class="materia-item">Artística</li>
                        <li class="materia-item">Ciencias Sociales</li>
                        <li class="materia-item">Matemática</li>
                        <li class="materia-item">Ciencias Naturales</li>
                        <li class="materia-item">Educación Física</li>
                        <li class="materia-item">Prácticas de Lenguaje</li>
                        <li class="materia-item">Construcción Ciudadana</li>
                    </ul>
                </div>
                
                <!-- 2° AÑO -->
                <div class="ciclo-card">
                    <h2 class="ciclo-title">2° AÑO</h2>
                    <ul class="materias-list">
                        <li class="materia-item">Matemáticas</li>
                        <li class="materia-item">Inglés</li>
                        <li class="materia-item">Construcción Ciudadana</li>
                        <li class="materia-item">Historia</li>
                        <li class="materia-item">Educación Artística</li>
                        <li class="materia-item">Lenguaje</li>
                        <li class="materia-item">Teatro</li>
                        <li class="materia-item">Geografía</li>
                        <li class="materia-item">Físico Química</li>
                        <li class="materia-item">Biología</li>
                    </ul>
                </div>
                
                <!-- 3° AÑO -->
                <div class="ciclo-card">
                    <h2 class="ciclo-title">3° AÑO</h2>
                    <ul class="materias-list">
                        <li class="materia-item">Prácticas de Lenguajes</li>
                        <li class="materia-item">Teatro</li>
                        <li class="materia-item">Construcción Ciudadana</li>
                        <li class="materia-item">Inglés</li>
                        <li class="materia-item">Historia</li>
                        <li class="materia-item">Matemática</li>
                        <li class="materia-item">Biología</li>
                        <li class="materia-item">Físico Química</li>
                        <li class="materia-item">Geografía</li>
                    </ul>
                </div>
            </div>
            
            <!-- Taller Ciclo Básico -->
            <div class="taller-section">
                <h2 class="taller-title">Taller Ciclo Básico</h2>
                <div class="taller-grid">
                    <div class="taller-item">Carpintería</div>
                    <div class="taller-item">Procedimientos Técnicos</div>
                    <div class="taller-item">Lenguaje Tecnológico</div>
                    <div class="taller-item">Sistemas Tecnológicos</div>
                </div>
            </div>
            
            <div class="info-section">
                <h3 class="info-title">Sobre el Ciclo Básico</h3>
                <p class="info-text">
                    El Ciclo Básico de la E.E.S.T. N°14 comprende los primeros tres años de la educación secundaria técnica. 
                    Durante este período, los estudiantes reciben una formación integral que combina conocimientos generales 
                    con una base sólida en ciencias, matemáticas, lenguas y artes. Este ciclo prepara a los alumnos para 
                    el Ciclo Superior, donde podrán especializarse en las diferentes tecnicaturas que ofrece nuestra institución.
                </p>
                <p class="info-text">
                    Nuestro enfoque pedagógico promueve el desarrollo de competencias fundamentales, el pensamiento crítico 
                    y la creatividad, preparando a los estudiantes para enfrentar los desafíos del mundo actual y futuro.
                </p>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <!-- Columna 1: E.E.S.T. N°4 -->
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Formando técnicos competentes y ciudadanos responsables desde 1980. Excelencia educativa para el futuro de Argentina.</p>
                    <div class="contact-info">
                        <div class="contact-item">Bariloche 6615, B1758 González Catán, Provincia de Buenos Aires</div>
                        <div class="contact-item"><a href="tel:02202428307">02202 42-8307</a></div>
                        <div class="contact-item"><a href="mailto:eest14platenza@abc.gob.ar">eest14platenza@abc.gob.ar</a></div>
                    </div>
                </div>
                
                <!-- Columna 2: Educación -->
                <div class="footer-column">
                    <h3>Educación</h3>
                    <ul>
                        <li><a href="ciclo_basico.php">Ciclo Básico</a></li>
                        <li><a href="#ciclo-superior">Ciclo Superior</a></li>
                        <li><a href="#tecnologia-alimentos">Tecnología en Alimentos</a></li>
                        <li><a href="#informatica">Informática</a></li>
                        <li><a href="#programacion">Programación</a></li>
                    </ul>
                </div>
                
                <!-- Columna 3: Institucional -->
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="#historia">Historia</a></li>
                        <li><a href="#proposito">Propósito</a></li>
                        <li><a href="publicar_comunicado.php">Comunicados</a></li>
                        <li><a href="#proyectos">Proyectos</a></li>
                        <li><a href="preinscripcion.php">Inscripciones</a></li>
                    </ul>
                </div>
                
                <!-- Columna 4: Mapa -->
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
