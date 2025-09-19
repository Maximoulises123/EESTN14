<?php
require_once '../src/config.php';

// Procesar búsqueda
$busqueda = $_GET['busqueda'] ?? '';
$comunicado_busqueda = $_GET['comunicado'] ?? '';
$where_conditions = [];
$params = [];

if (!empty($busqueda)) {
    $where_conditions[] = "Nombre LIKE ? OR descripcion LIKE ?";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
}

if (!empty($comunicado_busqueda)) {
    // Buscar por formato numero/año (ej: 1/2025)
    if (strpos($comunicado_busqueda, '/') !== false) {
        list($numero, $año) = explode('/', $comunicado_busqueda);
        $where_conditions[] = "numero_comunicado = ? AND año = ?";
        $params[] = $numero;
        $params[] = $año;
    } else {
        // Si solo se ingresa un número, buscar por número de comunicado
        $where_conditions[] = "numero_comunicado = ?";
        $params[] = $comunicado_busqueda;
    }
}

// Construir consulta
$sql = "SELECT * FROM comunicado";
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}
$sql .= " ORDER BY Fecha DESC, Id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$comunicados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicados - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/comunicados.css">
    <link rel="stylesheet" href="assets/css/admin.css">
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
        <div class="page-header">
            <h1>Noticias y Comunicados</h1>
            <p>Mantente informado sobre todas las novedades de nuestra institución</p>
        </div>
        
        <?php if (isset($_SESSION['tipo']) && ($_SESSION['tipo'] === 'admin' || $_SESSION['tipo'] === 'director')): ?>
            <div class="admin-section">
                <a href="admin_panel.php" class="btn-admin">Panel de Administración</a>
                <a href="publicar_comunicado.php" class="btn-admin">Publicar Comunicado</a>
                <a href="editar_comunicados.php" class="btn-admin">Gestionar Comunicados</a>
            </div>
        <?php endif; ?>
        
        <!-- Buscador de Comunicados -->
        <div class="buscador-section">
            <form method="GET" class="buscador-form">
                <div class="buscador-grid">
                    <div class="buscador-item">
                        <label for="busqueda">Buscar por nombre o descripción:</label>
                        <input type="text" name="busqueda" id="busqueda" 
                               value="<?php echo htmlspecialchars($busqueda); ?>" 
                               placeholder="Ingrese texto a buscar...">
                    </div>
                    <div class="buscador-item">
                        <label for="comunicado">Buscar por comunicado (ej: 1/2025):</label>
                        <input type="text" name="comunicado" id="comunicado" 
                               value="<?php echo htmlspecialchars($comunicado_busqueda); ?>"
                               placeholder="Ej: 1/2025">
                    </div>
                    <div class="buscador-item">
                        <label>&nbsp;</label>
                        <div class="buscador-botones">
                            <button type="submit" class="btn-buscar">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                </svg>
                                Buscar
                            </button>
                            <a href="comunicados.php" class="btn-limpiar">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            
            <?php if (!empty($busqueda) || !empty($comunicado_busqueda)): ?>
                <div class="resultados-busqueda">
                    <p><strong>Resultados de búsqueda:</strong></p>
                    <?php if (!empty($busqueda)): ?>
                        <span class="filtro-activo">Texto: "<?php echo htmlspecialchars($busqueda); ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($comunicado_busqueda)): ?>
                        <span class="filtro-activo">Comunicado: <?php echo htmlspecialchars($comunicado_busqueda); ?></span>
                    <?php endif; ?>
                    <span class="total-resultados"><?php echo count($comunicados); ?> resultado(s) encontrado(s)</span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (empty($comunicados)): ?>
            <?php if (!empty($busqueda) || !empty($comunicado_busqueda)): ?>
                <div class="sin-resultados">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        No se encontraron resultados
                    </h3>
                    <p>No hay comunicados que coincidan con tu búsqueda.</p>
                    <p>Intenta con otros términos o <a href="comunicados.php">ver todos los comunicados</a>.</p>
                </div>
            <?php else: ?>
                <div class="sin-comunicados">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            <path d="M13 8H7"/>
                            <path d="M17 12H7"/>
                        </svg>
                        No hay comunicados disponibles
                    </h3>
                    <p>Los comunicados oficiales aparecerán aquí cuando estén disponibles.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="comunicados-container">
                <?php foreach ($comunicados as $comunicado): ?>
                    <div class="comunicado-item">
                        <img src="<?php echo $comunicado['imagen'] ? htmlspecialchars($comunicado['imagen']) : 'Logo14.jpg'; ?>" 
                             alt="<?php echo $comunicado['imagen'] ? 'Imagen del comunicado' : 'Logo EEST14'; ?>" 
                             class="comunicado-imagen">
                        
                        <div class="comunicado-header">
                            <div class="titulo-fecha">
                                <h3><?php echo htmlspecialchars($comunicado['Nombre']); ?></h3>
                                <p class="fecha">
                                    <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($comunicado['Fecha'])); ?>
                                    <?php if ($comunicado['numero_comunicado'] && $comunicado['año']): ?>
                                        | <strong>Comunicado:</strong> <?php echo $comunicado['numero_comunicado'] . '/' . $comunicado['año']; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="badges">
                                <span class="importancia-<?php echo $comunicado['Importancia']; ?>">
                                    <?php echo ucfirst($comunicado['Importancia']); ?>
                                </span>
                                <?php if ($comunicado['destacado']): ?>
                                    <span class="badge-destacado">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        Destacado
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($comunicado['descripcion']): ?>
                            <div class="comunicado-descripcion">
                                <?php echo nl2br(htmlspecialchars($comunicado['descripcion'])); ?>
                            </div>
                            <div class="ver-mas">
                                <a href="#" class="ver-mas-link">Ver más</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
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
