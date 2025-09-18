<?php
require_once '../src/config.php';

// Verificar si está logueado y es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$mensaje = '';

// Procesar formularios
if ($_POST) {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'agregar_comunicado':
                $nombre = $_POST['nombre'] ?? '';
                $importancia = $_POST['importancia'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $fecha = date('Y-m-d');
                $imagen = '';
                
                // Procesar imagen si se subió
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'assets/img/comunicados/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array(strtolower($extension), $allowedExtensions)) {
                        $fileName = time() . '_' . $_FILES['imagen']['name'];
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                            $imagen = $uploadPath;
                        }
                    }
                }
                
                if ($nombre && $importancia) {
                    $stmt = $pdo->prepare("INSERT INTO comunicado (Nombre, Importancia, Fecha, descripcion, imagen) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $importancia, $fecha, $descripcion, $imagen]);
                    $mensaje = 'Comunicado agregado correctamente';
                }
                break;
                
            case 'cambiar_estado_preinscripcion':
                $id = $_POST['id'] ?? '';
                $estado = $_POST['estado'] ?? '';
                $inscripto = $_POST['inscripto'] ?? 0;
                
                if ($id && $estado) {
                    $stmt = $pdo->prepare("UPDATE preinsicripcion SET estado = ?, inscripto = ? WHERE Id = ?");
                    $stmt->execute([$estado, $inscripto, $id]);
                    $mensaje = 'Estado actualizado correctamente';
                }
                break;
        }
    }
}

// Obtener comunicados
$stmt = $pdo->query("SELECT * FROM comunicado ORDER BY Fecha DESC");
$comunicados = $stmt->fetchAll();

// Obtener pre-inscripciones
$stmt = $pdo->query("SELECT * FROM preinsicripcion ORDER BY Id DESC");
$preinscripciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
    <link rel="stylesheet" href="assets/css/footer.css">
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
        <div class="admin-container">
            <div class="admin-header">
                <h1>Panel Administrativo - EEST14</h1>
            </div>
        
        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <!-- Sección de Comunicados -->
        <div class="section">
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar_comunicado">
                <div class="form-group">
                    <label for="nombre">Título del Comunicado:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="form-group">
                    <label for="importancia">Importancia:</label>
                    <select name="importancia" id="importancia" required>
                        <option value="">Seleccione importancia</option>
                        <option value="urgente">Urgente</option>
                        <option value="normal">Normal</option>
                        <option value="informativo">Informativo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen (opcional):</label>
                    <input type="file" name="imagen" id="imagen" accept="image/*">
                    <small>Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                </div>
                <button type="submit" class="btn">Agregar Comunicado</button>
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
