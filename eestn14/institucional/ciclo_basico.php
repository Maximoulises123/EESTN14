<?php
require_once '../src/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclo Básico - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/ciclo_basico.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="main-content">
            
            <div class="ciclos-container">
                <!-- 1° AÑO -->
                <div class="ciclo-card">
                    <h2 class="ciclo-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        1° AÑO
                    </h2>
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
                    <h2 class="ciclo-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        2° AÑO
                    </h2>
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
                    <h2 class="ciclo-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        3° AÑO
                    </h2>
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
                <h2 class="taller-title">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    Taller Ciclo Básico
                </h2>
                <div class="taller-grid">
                    <div class="taller-item">
                        <h3>Procedimientos Técnicos</h3>
                        <div class="taller-content">
                            <h4>Algunos temas / contenidos comunes:</h4>
                            <ul>
                                <li>Normas de seguridad e higiene en talleres</li>
                                <li>Materiales (tipos, propiedades) y selección de los mismos</li>
                                <li>Uso de herramientas manuales y eléctricas</li>
                                <li>Máquinas simples</li>
                                <li>Operaciones básicas como corte, ensamblado, lijado</li>
                                <li>Organización del trabajo en taller</li>
                                <li>Hacer pequeños proyectos prácticos</li>
                                <li>Mantenimiento</li>
                                <li>Trabajo con máquinas-herramienta en cursos posteriores del ciclo básico</li>
                            </ul>
                        </div>
                    </div>
                    <div class="taller-item">
                        <h3>Lenguajes Tecnológicos</h3>
                        <div class="taller-content">
                            <h4>Algunos temas / contenidos comunes:</h4>
                            <ul>
                                <li>Dibujo técnico (vistas, acotamiento, secciones básicas)</li>
                                <li>Símbolos normativos</li>
                                <li>Interpretación de planos simples</li>
                                <li>Lenguaje gráfico y modelización</li>
                                <li>Representación técnica de objetos</li>
                                <li>Diagramas, esquemas</li>
                                <li>Comunicación técnica (manuales, etiquetado)</li>
                                <li>Normas de representación</li>
                            </ul>
                        </div>
                    </div>
                    <div class="taller-item">
                        <h3>Sistemas Tecnológicos</h3>
                        <div class="taller-content">
                            <h4>Algunos temas / contenidos comunes:</h4>
                            <ul>
                                <li>Concepto de sistema: componentes, funciones</li>
                                <li>Entradas-procesos-salidas</li>
                                <li>Regulación, control</li>
                                <li>Energía, materia, información</li>
                                <li>Interrelación entre sistemas</li>
                                <li>Ejemplos: sistemas eléctricos simples, sistemas mecánicos, hidráulicos</li>
                                <li>Análisis de cómo funciona un sistema completo</li>
                                <li>Impacto de los sistemas en el medio ambiente y sociedad</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-section">
                <h3 class="info-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Sobre el Ciclo Básico
                </h3>
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
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
