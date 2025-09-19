<?php
require_once '../src/config.php';

// Obtener proyectos de saberes
$stmt = $pdo->query("SELECT * FROM proyectos WHERE categoria = 'saberes' AND activo = 1 ORDER BY destacado DESC, fecha_creacion DESC");
$proyectos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saberes - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <style>
        .page-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 40px 20px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            border-radius: 15px;
        }
        
        .page-header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .page-header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .proyectos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .proyecto-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .proyecto-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .proyecto-card.destacado {
            border: 3px solid #f39c12;
            background: linear-gradient(135deg, #fef9e7 0%, #ffffff 100%);
        }
        
        .proyecto-imagen {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .proyecto-card:hover .proyecto-imagen {
            transform: scale(1.05);
        }
        
        .proyecto-content {
            padding: 25px;
        }
        
        .proyecto-titulo {
            font-size: 1.4em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .proyecto-descripcion {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .proyecto-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
        }
        
        .proyecto-fecha {
            color: #95a5a6;
            font-size: 0.9em;
        }
        
        .badge-destacado {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }
        
        .categoria-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .empty-state h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #95a5a6;
        }
        
        .empty-state p {
            font-size: 1.1em;
            line-height: 1.6;
        }
        
        .section-intro {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #ff6b6b;
        }
        
        .section-intro h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 2em;
        }
        
        .section-intro p {
            color: #7f8c8d;
            line-height: 1.6;
            font-size: 1.1em;
        }
        
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2em;
            }
            
            .proyectos-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .page-container {
                padding: 10px;
            }
        }
    </style>
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
                            <li><a href="expo_tecnica.php">Expotécnica</a></li>
                            <li><a href="saberes.php">Saberes</a></li>
                            <li><a href="precapacidades.php">Pre Capacidades</a></li>
                            <li><a href="capacidades.php">Capacidades</a></li>
                            <li><a href="destacados.php">Destacados</a></li>
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
                    <?php if (isset($_SESSION['tipo']) && ($_SESSION['tipo'] === 'director' || $_SESSION['tipo'] === 'admin')): ?>
                        <a href="admin_panel.php" class="btn-admin">Panel Admin</a>
                    <?php endif; ?>
                    <a href="perfil.php" class="btn-perfil">Perfil</a>
                <?php else: ?>
                    <a href="registro.php" class="btn-registro">Registrarse</a>
                    <a href="login.php" class="btn-login">Iniciar sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <main>
        <div class="page-container">
            <div class="page-header">
                <h1>
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 12px;">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    Saberes
                </h1>
                <p>Proyectos que integran conocimientos teóricos y prácticos de las diferentes áreas</p>
            </div>
            
            <div class="section-intro">
                <h2>Integración de Conocimientos</h2>
                <p>Los proyectos de saberes están diseñados para integrar conocimientos teóricos y prácticos de diferentes áreas del conocimiento. Estos proyectos permiten a los estudiantes desarrollar una comprensión holística de los saberes, conectando conceptos de diferentes disciplinas y aplicándolos en contextos reales.</p>
            </div>
            
            <?php if (empty($proyectos)): ?>
                <div class="empty-state">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12,6 12,12 16,14"/>
                        </svg>
                        Próximamente
                    </h3>
                    <p>Estamos trabajando en nuevos proyectos de saberes. Los proyectos aparecerán aquí cuando estén disponibles.</p>
                </div>
            <?php else: ?>
                <div class="proyectos-grid">
                    <?php foreach ($proyectos as $proyecto): ?>
                        <div class="proyecto-card <?php echo $proyecto['destacado'] ? 'destacado' : ''; ?>">
                            <?php if ($proyecto['destacado']): ?>
                                <div class="badge-destacado">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Destacado
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($proyecto['imagen']): ?>
                                <img src="<?php echo htmlspecialchars($proyecto['imagen']); ?>" 
                                     alt="<?php echo htmlspecialchars($proyecto['titulo']); ?>" 
                                     class="proyecto-imagen">
                            <?php endif; ?>
                            
                            <div class="proyecto-content">
                                <div class="categoria-badge">Saberes</div>
                                <h3 class="proyecto-titulo"><?php echo htmlspecialchars($proyecto['titulo']); ?></h3>
                                <p class="proyecto-descripcion">
                                    <?php echo htmlspecialchars($proyecto['descripcion']); ?>
                                </p>
                                <div class="proyecto-meta">
                                    <span class="proyecto-fecha">
                                        <?php echo date('d/m/Y', strtotime($proyecto['fecha_creacion'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                        <li><a href="ciclo_basico.php">Ciclo Básico</a></li>
                        <li><a href="ciclo_superior.php">Ciclo Superior</a></li>
                        <li><a href="index.php#tecnologia-alimentos">Tecnología en Alimentos</a></li>
                        <li><a href="index.php#informatica">Informática</a></li>
                        <li><a href="index.php#programacion">Programación</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="index.php#historia">Historia</a></li>
                        <li><a href="index.php#proposito">Propósito</a></li>
                        <li><a href="comunicados.php">Comunicados</a></li>
                        <li><a href="expo_tecnica.php">Expotécnica</a></li>
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
