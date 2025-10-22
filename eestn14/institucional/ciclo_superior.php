<?php
require_once '../src/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclo Superior - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/ciclo_superior.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="main-content">
            <!-- Divisiones Técnicas -->
            <div class="divisiones-container">
                <div class="division-card" data-division="programacion">
                    <div class="division-header">
                        <h2 class="division-title">Programación</h2>
                        <span class="division-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="16,18 22,12 16,6"/>
                                <polyline points="8,6 2,12 8,18"/>
                            </svg>
                        </span>
                    </div>
                    <p class="division-description">Desarrollo de software y aplicaciones</p>
                    <div class="division-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"/>
                        </svg>
                    </div>
                </div>

                <div class="division-card" data-division="alimentos">
                    <div class="division-header">
                        <h2 class="division-title">Alimentos</h2>
                        <span class="division-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                        </span>
                    </div>
                    <p class="division-description">Tecnología en procesamiento de alimentos</p>
                    <div class="division-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"/>
                        </svg>
                    </div>
                </div>

                <div class="division-card" data-division="informatica">
                    <div class="division-header">
                        <h2 class="division-title">Informática</h2>
                        <span class="division-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </span>
                    </div>
                    <p class="division-description">Sistemas informáticos y redes</p>
                    <div class="division-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6,9 12,15 18,9"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Materias por División -->
            <div class="materias-container">
                <!-- Programación -->
                <div class="materias-division" id="programacion-materias">
                    <h3 class="materias-title">Materias de Programación</h3>
                    <p><a href="../Pdfs/programacion.pdf" target="_blank" class="materias-title">Diseño curricular</a></p>
                    
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
                    <p><a href="../Pdfs/tecnologia_de_los_alimentos.pdf" target="_blank" class="materias-title">Diseño curricular</a></p>
                    
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
                    <p><a href="../Pdfs/informatica_personal_y_profesional.pdf" target="_blank" class="materias-title">Diseño curricular</a></p>
                    
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

    <?php include '../includes/footer.php'; ?>

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
                        const arrowSvg = c.querySelector('.division-arrow svg');
                        if (arrowSvg) {
                            arrowSvg.style.transform = 'rotate(0deg)';
                        }
                        c.classList.remove('active');
                    });
                    
                    // Mostrar la sección seleccionada
                    if (targetDivision) {
                        targetDivision.style.display = 'block';
                        const arrowSvg = arrow.querySelector('svg');
                        if (arrowSvg) {
                            arrowSvg.style.transform = 'rotate(180deg)';
                        }
                        this.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
