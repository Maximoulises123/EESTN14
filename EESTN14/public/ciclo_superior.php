<?php
require_once '../src/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclo Superior - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/ciclo_superior.css">
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
                            <li><a href="ciclo_superior.php">Ciclo Superior</a></li>
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
                <h1 class="hero-title">Ciclo Superior</h1>
                <p class="hero-subtitle">Especialización técnica para el futuro profesional</p>
            </div>
        </section>
        
        <!-- Info Section -->
        <section class="info-hero">
            <div class="info-hero-content">
                <h2 class="info-hero-title">Especialización Técnica</h2>
                <p class="info-hero-text">El ciclo superior comprende los últimos tres años de la educación secundaria técnica, donde los estudiantes se especializan en una de las tres modalidades técnicas que ofrece nuestra institución, adquiriendo competencias específicas para su futuro profesional.</p>
            </div>
        </section>
        
        <div class="main-content">
            <!-- Divisiones Técnicas -->
            <div class="divisiones-container">
                <div class="division-card" data-division="programacion">
                    <div class="division-header">
                        <h2 class="division-title">Programación</h2>
                        <span class="division-icon">💻</span>
                    </div>
                    <p class="division-description">Desarrollo de software y aplicaciones</p>
                    <div class="division-arrow">▼</div>
                </div>
                
                <div class="division-card" data-division="alimentos">
                    <div class="division-header">
                        <h2 class="division-title">Alimentos</h2>
                        <span class="division-icon">🍎</span>
                    </div>
                    <p class="division-description">Tecnología en procesamiento de alimentos</p>
                    <div class="division-arrow">▼</div>
                </div>
                
                <div class="division-card" data-division="informatica">
                    <div class="division-header">
                        <h2 class="division-title">Informática</h2>
                        <span class="division-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </span>
                    </div>
                    <p class="division-description">Sistemas informáticos y redes</p>
                    <div class="division-arrow">▼</div>
                </div>
            </div>
            
            <!-- Materias por División -->
            <div class="materias-container">
                <!-- Programación -->
                <div class="materias-division" id="programacion-materias">
                    <h3 class="materias-title">Materias de Programación</h3>
                    <div class="anos-container"> 
                        <div class="ano-card">
                            <h4 class="ano-title">4° AÑO</h4>
                            <ul class="materias-list">
                               
                                <li class="materia-item">Hardware</li>
                                <li class="materia-item">Software</li>
                                <li class="materia-item">Metodología en Programación</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">5° AÑO</h4>
                            <ul class="materias-list">
                                
                                <li class="materia-item">Base de Datos</li>
                                <li class="materia-item">Lenguaje de Programación 1°</li>
                                <li class="materia-item">Diseño Web</li>
                                <li class="materia-item">Redes Informáticas</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">6° AÑO</h4>
                            <ul class="materias-list">
                                
                                <li class="materia-item">Laboratorio de Aplicaciones Web Dinámicas</li>
                                <li class="materia-item">Laboratorio de Aplicaciones Web Estáticas</li>
                                <li class="materia-item">Laboratorio de Programación 3</li>
                                <li class="materia-item">Laboratorio de Procesos Industriales</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">7° AÑO</h4>
                            <ul class="materias-list">
                                
                                <li class="materia-item">Proyecto de Desarrollo para Plataformas Móviles</li>
                                <li class="materia-item">Organización y Métodos</li>
                                <li class="materia-item">Proyecto, Diseño e Implementación de Sistemas Computacionales</li>
                                <li class="materia-item">Proyecto e Implementación de Sitio Web Dinámicos</li>
                                <li class="materia-item">Evaluación de Proyectos</li>
                                <li class="materia-item">Emprendimientos Productivos y Desarrollo Local</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Alimentos -->
                <div class="materias-division" id="alimentos-materias">
                    <h3 class="materias-title">Materias de Alimentos</h3>
                    <div class="anos-container">
                        <div class="ano-card">
                            <h4 class="ano-title">4° AÑO</h4>
                            <ul class="materias-list">
                               
                                <li class="materia-item">Química de Alimentos</li>
                                <li class="materia-item">Microbiología Alimentaria</li>
                                <li class="materia-item">Práctica Profesionalizante I</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">5° AÑO</h4>
                            <ul class="materias-list">
                                
                                <li class="materia-item">Tecnología de Alimentos</li>
                                <li class="materia-item">Procesamiento de Alimentos</li>
                                <li class="materia-item">Práctica Profesionalizante II</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">6° AÑO</h4>
                            <ul class="materias-list">
                               
                                <li class="materia-item">Control de Calidad</li>
                                <li class="materia-item">Desarrollo de Productos</li>
                                <li class="materia-item">Práctica Profesionalizante III</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Informática -->
                <div class="materias-division" id="informatica-materias">
                    <h3 class="materias-title">Materias de Informática</h3>
                    <div class="anos-container">
                        <div class="ano-card">
                            <h4 class="ano-title">4° AÑO</h4>
                            <ul class="materias-list">
                               
                                <li class="materia-item">Sistemas Informáticos</li>
                                <li class="materia-item">Arquitectura de Computadoras</li>
                                <li class="materia-item">Práctica Profesionalizante I</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">5° AÑO</h4>
                            <ul class="materias-list">
                               
                                <li class="materia-item">Redes de Computadoras</li>
                                <li class="materia-item">Sistemas Operativos</li>
                                <li class="materia-item">Práctica Profesionalizante II</li>
                            </ul>
                        </div>
                        
                        <div class="ano-card">
                            <h4 class="ano-title">6° AÑO</h4>
                            <ul class="materias-list">
                               
                                <li class="materia-item">Administración de Redes</li>
                                <li class="materia-item">Seguridad Informática</li>
                                <li class="materia-item">Práctica Profesionalizante III</li>
                            </ul>
                        </div>
                    </div>
                </div>
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
                        <li><a href="ciclo_superior.php">Ciclo Superior</a></li>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const divisionCards = document.querySelectorAll('.division-card');
            const materiasDivisions = document.querySelectorAll('.materias-division');
            
            // Ocultar todas las secciones de materias inicialmente
            materiasDivisions.forEach(division => {
                division.style.display = 'none';
            });
            
            divisionCards.forEach(card => {
                card.addEventListener('click', function() {
                    const division = this.getAttribute('data-division');
                    const targetDivision = document.getElementById(division + '-materias');
                    const arrow = this.querySelector('.division-arrow');
                    
                    // Ocultar todas las secciones de materias
                    materiasDivisions.forEach(div => {
                        div.style.display = 'none';
                    });
                    
                    // Resetear todas las flechas
                    divisionCards.forEach(c => {
                        c.querySelector('.division-arrow').textContent = '▼';
                        c.classList.remove('active');
                    });
                    
                    // Mostrar la sección seleccionada
                    if (targetDivision) {
                        targetDivision.style.display = 'block';
                        arrow.textContent = '▲';
                        this.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
