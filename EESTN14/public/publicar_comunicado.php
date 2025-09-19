<?php
require_once '../src/config.php';

// Verificar si está logueado y es administrador o director
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'director')) {
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
                $año = $_POST['año'] ?? date('Y');
                $destacado = isset($_POST['destacado']) ? 1 : 0;
                $imagen = '';
                
                // Generar número de comunicado automáticamente
                $stmt = $pdo->prepare("SELECT MAX(numero_comunicado) as ultimo_numero FROM comunicado WHERE año = ?");
                $stmt->execute([$año]);
                $result = $stmt->fetch();
                $numero_comunicado = ($result['ultimo_numero'] ?? 0) + 1;
                
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
                    $stmt = $pdo->prepare("INSERT INTO comunicado (Nombre, Importancia, Fecha, descripcion, imagen, numero_comunicado, año, destacado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $importancia, $fecha, $descripcion, $imagen, $numero_comunicado, $año, $destacado]);
                    $mensaje = "Comunicado agregado correctamente - Número: {$numero_comunicado}/{$año}";
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
    <style>
        .admin-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .admin-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
        }
        
        .admin-header h1 {
            margin: 0;
            font-size: 2.2em;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .mensaje {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(39, 174, 96, 0.3);
            animation: fadeInUp 0.5s ease-out;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1em;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #fff;
            box-sizing: border-box;
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-1px);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #bdc3c7;
            border-radius: 8px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-group input[type="file"]:hover {
            border-color: #3498db;
            background: #ecf0f1;
        }
        
        .form-group small {
            display: block;
            margin-top: 5px;
            color: #7f8c8d;
            font-size: 0.9em;
            font-style: italic;
        }
        
        .form-group input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
            cursor: pointer;
        }
        
        .form-group label:has(input[type="checkbox"]) {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .form-group label:has(input[type="checkbox"]):hover {
            background: #ecf0f1;
            border-color: #3498db;
        }
        
        .btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            width: 100%;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        .btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(231, 76, 60, 0.3);
        }
        
        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .section {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-container {
                margin: 10px;
                padding: 15px;
            }
            
            .admin-header h1 {
                font-size: 1.8em;
            }
            
            .section {
                padding: 20px;
            }
            
            .form-group input[type="text"],
            .form-group input[type="number"],
            .form-group select,
            .form-group textarea {
                padding: 10px 12px;
                font-size: 0.95em;
            }
        }
        
        /* Estilos para el dropdown */
        .form-group select option {
            padding: 10px;
            background: white;
            color: #2c3e50;
        }
        
        /* Estilo para el archivo seleccionado */
        .form-group input[type="file"]::-webkit-file-upload-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background 0.3s ease;
        }
        
        .form-group input[type="file"]::-webkit-file-upload-button:hover {
            background: #2980b9;
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
                    <?php if ($_SESSION['tipo'] === 'director' || $_SESSION['tipo'] === 'admin'): ?>
                        <a href="admin_panel.php" class="btn-admin">Panel Admin</a>
                    <?php endif; ?>
                    <a href="perfil.php" class="btn-perfil">Perfil</a>
                    <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
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
                    <label for="año">Año:</label>
                    <input type="number" name="año" id="año" value="<?php echo date('Y'); ?>" min="2020" max="2030" required>
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
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="destacado" id="destacado"> 
                        Marcar como destacado
                    </label>
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
