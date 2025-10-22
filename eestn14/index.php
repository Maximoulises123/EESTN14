
<?php
require_once 'src/config.php';

// Obtener comunicados con manejo de errores
$comunicados = [];
if ($pdo !== null) {
    try {
        $stmt = $pdo->prepare("SELECT id, titulo, descripcion, imagen, fecha, importancia FROM comunicados ORDER BY fecha DESC, id DESC LIMIT 5");
        $stmt->execute();
        $comunicados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener comunicados: " . $e->getMessage());
        $comunicados = [];
    }
} else {
    error_log("No hay conexión a la base de datos disponible");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEST14 - Escuela de Educación Secundaria Técnica</title>
    <meta name="description" content="Formación técnica de excelencia en Alimentos, Informática y Programación. Educación secundaria técnica de calidad.">
    <meta name="keywords" content="educación técnica, secundaria técnica, alimentos, informática, programación, EEST14">
    <meta name="author" content="EEST14">
    
    <!-- Preload critical CSS -->
    <link rel="preload" href="assets/css/global.css" as="style">
    <link rel="preload" href="assets/css/encabezado.css" as="style">
    
    <!-- CSS Global - Tipografía profesional -->
    <link rel="stylesheet" href="assets/css/global.css?v=<?php echo time(); ?>">
    
    <!-- CSS específico -->
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/index.css?v=<?php echo time(); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo/LOGO.png">
</head>
<body>
    <?php include 'includes/encabezado.php'; ?>
    
    <main>
        <!-- Carrusel de Comunicados -->
        <section class="carrusel-section" aria-label="Comunicados importantes">
            <div class="carrusel-container">
                <div class="carrusel-wrapper">
                    <div class="carrusel" role="region" aria-label="Carrusel de comunicados">
                        <?php if (!empty($comunicados)): ?>
                            <?php foreach ($comunicados as $index => $comunicado): ?>
                                <article class="carrusel-slide" <?php echo $index === 0 ? 'aria-current="true"' : ''; ?>>
                                    <div class="carrusel-content">
                                        <?php if (!empty($comunicado['imagen'])): ?>
                                            <img src="<?php echo htmlspecialchars($comunicado['imagen']); ?>?v=<?php echo time(); ?>" 
                                                 alt="<?php echo htmlspecialchars($comunicado['titulo']); ?>" 
                                                 class="carrusel-imagen"
                                                 loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                                        <?php else: ?>
                                            <div class="carrusel-imagen-placeholder" role="img" aria-label="Imagen del comunicado">
                                                <div class="placeholder-content">
                                                    <svg class="placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                                        <polyline points="21,15 16,10 5,21"/>
                                                    </svg>
                                                    <p>Imagen del comunicado</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="carrusel-info">
                                            <div class="carrusel-content-wrapper">
                                                <header class="carrusel-header">
                                                    <div class="carrusel-titulo-wrapper">
                                                        <h3 class="carrusel-titulo-principal"><?php echo htmlspecialchars($comunicado['titulo']); ?></h3>
                                                        <?php if (!empty($comunicado['importancia'])): ?>
                                                            <span class="carrusel-importancia importancia-<?php echo strtolower($comunicado['importancia']); ?>">
                                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                                                    <line x1="12" y1="9" x2="12" y2="13"/>
                                                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                                                </svg>
                                                                <?php echo htmlspecialchars($comunicado['importancia']); ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <time class="carrusel-fecha" datetime="<?php echo date('Y-m-d', strtotime($comunicado['fecha'])); ?>">
                                                        <?php echo date('d/m/Y', strtotime($comunicado['fecha'])); ?>
                                                    </time>
                                                </header>
                                                
                                                <?php if (!empty($comunicado['descripcion'])): ?>
                                                    <p class="carrusel-descripcion">
                                                        <?php echo htmlspecialchars($comunicado['descripcion']); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <a href="institucional/comunicados.php" class="carrusel-ver-mas" aria-label="Ver más comunicados">
                                                Ver más
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="carrusel-slide">
                                <div class="carrusel-content">
                                    <p>No hay comunicados disponibles en este momento.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Controles del carrusel -->
                    <button class="carrusel-btn carrusel-prev" aria-label="Comunicado anterior" type="button">‹</button>
                    <button class="carrusel-btn carrusel-next" aria-label="Siguiente comunicado" type="button">›</button>
                    
                    <!-- Indicadores -->
                    <?php if (count($comunicados) > 1): ?>
                        <nav class="carrusel-indicators" aria-label="Navegación del carrusel">
                        <?php for ($i = 0; $i < count($comunicados); $i++): ?>
                                <button class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" 
                                        aria-label="Ir al comunicado <?php echo $i + 1; ?>" 
                                        type="button"
                                        data-slide="<?php echo $i; ?>"></button>
                        <?php endfor; ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Sección de Especialidades -->
        <section class="especialidades-section">
            <div class="especialidades-container">
                <div class="especialidades-grid" role="list" aria-label="Lista de especialidades técnicas">
                    <article class="especialidad-card" role="listitem">
                        <div class="especialidad-header">
                            <div class="especialidad-imagen">
                                <div class="especialidad-icon" aria-hidden="true">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4"/>
                                        <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
                                        <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
                                        <path d="M12 3c0 1-1 3-3 3s-3-2-3-3 1-3 3-3 3 2 3 3"/>
                                        <path d="M12 21c0-1 1-3 3-3s3 2 3 3-1 3-3 3-3-2-3-3"/>
                                    </svg>
                                </div>
                                <div class="especialidad-placeholder" aria-hidden="true">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M3 12h18m-9-9v18"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="especialidad-content">
                            <h3>Tecnología en Alimentos</h3>
                            <p>Formación integral en procesamiento, conservación y control de calidad alimentaria. Nuestros egresados trabajan en las principales empresas del sector.</p>
                            
                        </div>
                    </article>
                    
                    <article class="especialidad-card" role="listitem">
                        <div class="especialidad-header">
                            <div class="especialidad-imagen">
                                <div class="especialidad-icon" aria-hidden="true">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                        <line x1="8" y1="21" x2="16" y2="21"/>
                                        <line x1="12" y1="17" x2="12" y2="21"/>
                                    </svg>
                                </div>
                                <div class="especialidad-placeholder" aria-hidden="true">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                        <line x1="8" y1="21" x2="16" y2="21"/>
                                        <line x1="12" y1="17" x2="12" y2="21"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="especialidad-content">
                            <h3>Informática</h3>
                            <p>Especialización en administración de sistemas, redes y seguridad informática. Tecnología empresarial de vanguardia.</p>
                            
                        </div>
                    </article>
                    
                    <article class="especialidad-card" role="listitem">
                        <div class="especialidad-header">
                            <div class="especialidad-imagen">
                                <img src="assets/img/programacion.png" alt="Programación" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        
                        <div class="especialidad-content">
                            <h3>Programación</h3>
                            <p>Desarrollo de software, aplicaciones web y móviles. Tecnologías modernas y metodologías ágiles para el futuro digital.</p>
                            
                        </div>
                    </article>
                </div>
            </div>
        </section>
        
        
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carrusel = document.querySelector('.carrusel');
            const slides = document.querySelectorAll('.carrusel-slide');
            const prevBtn = document.querySelector('.carrusel-prev');
            const nextBtn = document.querySelector('.carrusel-next');
            const indicators = document.querySelectorAll('.indicator');
            
            let currentSlide = 0;
            const totalSlides = slides.length;
            let autoPlayInterval = null;
            
            // Solo inicializar si hay más de un slide
            if (totalSlides <= 1) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                const indicatorsContainer = document.querySelector('.carrusel-indicators');
                if (indicatorsContainer) indicatorsContainer.style.display = 'none';
                return;
            }
            
            function updateCarrusel() {
                // Mover el carrusel
                carrusel.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                // Actualizar indicadores
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentSlide);
                });
                
                // Actualizar slides
                slides.forEach((slide, index) => {
                    slide.setAttribute('aria-current', index === currentSlide ? 'true' : 'false');
                });
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarrusel();
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarrusel();
            }
            
            function goToSlide(index) {
                if (index >= 0 && index < totalSlides) {
                    currentSlide = index;
                    updateCarrusel();
                }
            }
            
            function startAutoPlay() {
                stopAutoPlay();
                autoPlayInterval = setInterval(nextSlide, 5000);
            }
            
            function stopAutoPlay() {
                if (autoPlayInterval) {
                    clearInterval(autoPlayInterval);
                    autoPlayInterval = null;
                }
            }
            
            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    stopAutoPlay();
                    nextSlide();
                    startAutoPlay();
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    stopAutoPlay();
                    prevSlide();
                    startAutoPlay();
                });
            }
            
            // Indicadores
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    stopAutoPlay();
                    goToSlide(index);
                    startAutoPlay();
                });
            });
            
            // Pausar auto-play al hacer hover
            const wrapper = document.querySelector('.carrusel-wrapper');
            if (wrapper) {
                wrapper.addEventListener('mouseenter', stopAutoPlay);
                wrapper.addEventListener('mouseleave', startAutoPlay);
            }
            
            // Inicializar
            updateCarrusel();
            startAutoPlay();
        });
    </script>
    
</body>
</html>