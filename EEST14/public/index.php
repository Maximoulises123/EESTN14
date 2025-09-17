<?php
require_once '../src/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/carrusel.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="assets/img/LOGO.png" alt="E.E.S.T. N° 14 - GONZÁLEZ CATÁN" class="logo-img">
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
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
        <!-- Carrusel de Comunicados -->
        <section class="carrusel-section">
            <div class="carrusel-container">
                <h2>Últimos Comunicados</h2>
                <div class="carrusel-wrapper">
                    <div class="carrusel">
                        <?php
                        // Obtener los últimos 5 comunicados
                        $stmt = $pdo->query("SELECT * FROM comunicado ORDER BY Fecha DESC, Id DESC LIMIT 5");
                        $comunicados = $stmt->fetchAll();
                        ?>
                        
                        <?php if (!empty($comunicados)): ?>
                            <?php foreach ($comunicados as $comunicado): ?>
                                <div class="carrusel-slide">
                                    <div class="carrusel-content">
                                        <?php if ($comunicado['imagen']): ?>
                                            <img src="<?php echo htmlspecialchars($comunicado['imagen']); ?>" 
                                                 alt="Imagen del comunicado" 
                                                 class="carrusel-imagen">
                                        <?php endif; ?>
                                        
                                        <div class="carrusel-info">
                                            <div class="carrusel-header">
                                                <h3><?php echo htmlspecialchars($comunicado['Nombre']); ?></h3>
                                                <span class="importancia-<?php echo $comunicado['Importancia']; ?>">
                                                    <?php echo ucfirst($comunicado['Importancia']); ?>
                                                </span>
                                            </div>
                                            
                                            <p class="carrusel-fecha">
                                                <?php echo date('d/m/Y', strtotime($comunicado['Fecha'])); ?>
                                            </p>
                                            
                                            <?php if ($comunicado['descripcion']): ?>
                                                <p class="carrusel-descripcion">
                                                    <?php echo htmlspecialchars(substr($comunicado['descripcion'], 0, 150)) . '...'; ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <a href="comunicados.php" class="carrusel-ver-mas">Ver más</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carrusel-slide">
                                <div class="carrusel-content">
                                    <p>No hay comunicados disponibles</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Controles del carrusel -->
                    <button class="carrusel-btn carrusel-prev">‹</button>
                    <button class="carrusel-btn carrusel-next">›</button>
                    
                    <!-- Indicadores -->
                    <div class="carrusel-indicators">
                        <?php for ($i = 0; $i < count($comunicados); $i++): ?>
                            <span class="indicator <?php echo $i === 0 ? 'active' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </section>
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
                        <li><a href="#ciclo-basico">Ciclo Básico</a></li>
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
    
    <script>
        // Funcionalidad del carrusel
        document.addEventListener('DOMContentLoaded', function() {
            const carrusel = document.querySelector('.carrusel');
            const slides = document.querySelectorAll('.carrusel-slide');
            const prevBtn = document.querySelector('.carrusel-prev');
            const nextBtn = document.querySelector('.carrusel-next');
            const indicators = document.querySelectorAll('.indicator');
            
            // Verificar que existen los elementos necesarios
            if (!carrusel || slides.length === 0) {
                console.log('No hay comunicados para mostrar en el carrusel');
                return;
            }
            
            let currentSlide = 0;
            const totalSlides = slides.length;
            let autoPlayInterval;
            
            function updateCarrusel() {
                if (carrusel) {
                    carrusel.style.transform = `translateX(-${currentSlide * 100}%)`;
                }
                
                // Actualizar indicadores
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentSlide);
                });
            }
            
            function nextSlide() {
                if (totalSlides > 1) {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    updateCarrusel();
                }
            }
            
            function prevSlide() {
                if (totalSlides > 1) {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    updateCarrusel();
                }
            }
            
            function startAutoPlay() {
                if (totalSlides > 1) {
                    autoPlayInterval = setInterval(nextSlide, 5000);
                }
            }
            
            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                }
            }
            
            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    stopAutoPlay();
                    nextSlide();
                    startAutoPlay();
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    stopAutoPlay();
                    prevSlide();
                    startAutoPlay();
                });
            }
            
            // Indicadores
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    stopAutoPlay();
                    currentSlide = index;
                    updateCarrusel();
                    startAutoPlay();
                });
            });
            
            // Pausar auto-play al hacer hover
            const carruselWrapper = document.querySelector('.carrusel-wrapper');
            if (carruselWrapper) {
                carruselWrapper.addEventListener('mouseenter', stopAutoPlay);
                carruselWrapper.addEventListener('mouseleave', startAutoPlay);
            }
            
            // Inicializar
            updateCarrusel();
            startAutoPlay();
        });
    </script>
    
</body>
</html>