<?php
require_once '../src/config.php';

// Verificar si está logueado y es administrador o director
if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'director')) {
    header('Location: ../auth/login.php');
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
                
                // Número de comunicado automático
                $numero_comunicado = 1;
                
                // Procesar imagen si se subió
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/img/comunicados/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array(strtolower($extension), $allowedExtensions)) {
                        $fileName = time() . '_' . $_FILES['imagen']['name'];
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                            $imagen = 'assets/img/comunicados/' . $fileName;
                        }
                    }
                }
                
                if ($nombre && $importancia) {
                    $stmt = $pdo->prepare("INSERT INTO comunicados (titulo, importancia, fecha, descripcion, imagen, numero, anio, destacado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $importancia, $fecha, $descripcion, $imagen, $numero_comunicado, $año, $destacado]);
                    $mensaje = "Comunicado agregado correctamente";
                }
                break;
                
            case 'cambiar_estado_preinscripcion':
                $id = $_POST['id'] ?? '';
                $estado = $_POST['estado'] ?? '';
                $inscripto = $_POST['inscripto'] ?? 0;
                
                if ($id && $estado) {
                    $stmt = $pdo->prepare("UPDATE preinscripciones SET estado = ?, inscripto = ? WHERE id = ?");
                    $stmt->execute([$estado, $inscripto, $id]);
                    $mensaje = 'Estado actualizado correctamente';
                }
                break;
        }
    }
}

// Obtener comunicados
$stmt = $pdo->query("SELECT * FROM comunicados ORDER BY fecha DESC");
$comunicados = $stmt->fetchAll();

// Obtener pre-inscripciones
$stmt = $pdo->query("SELECT * FROM preinscripciones ORDER BY id DESC");
$preinscripciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/publicar_comunicado.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
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
            <div class="form-header">
                <h2>Publicar Nuevo Comunicado</h2>
                <p>Complete los campos para crear un nuevo comunicado oficial</p>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="comunicado-form">
                <input type="hidden" name="accion" value="agregar_comunicado">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Título del Comunicado</label>
                        <input type="text" name="nombre" id="nombre" placeholder="Ingrese el título del comunicado" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="importancia">Importancia</label>
                        <select name="importancia" id="importancia" required>
                            <option value="">Seleccione importancia</option>
                            <option value="Alta">Alta</option>
                            <option value="Media">Media</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="año">Año</label>
                        <input type="number" name="año" id="año" value="<?php echo date('Y'); ?>" min="2020" max="2030" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="imagen">Imagen (opcional)</label>
                        <input type="file" name="imagen" id="imagen" accept="image/*">
                        <small>Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" placeholder="Ingrese la descripción del comunicado"></textarea>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="destacado" id="destacado"> 
                        <span class="checkmark"></span>
                        Marcar como destacado
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        Publicar Comunicado
                    </button>
                    
                    <a href="../panels/admin_panel.php" class="btn btn-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Volver al Panel
                    </a>
                </div>
            </form>
        </div>
        
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
</body>
</html>

