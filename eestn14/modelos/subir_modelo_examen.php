<?php
require_once '../src/config.php';

// Verificar si el usuario es profesor
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'profesor') {
    header('Location: ../auth/login.php');
    exit;
}

$error = '';
$success = '';

// Obtener materias asignadas al profesor
$stmt = $pdo->prepare("
    SELECT m.*, pm.anio_academico
    FROM materias m
    JOIN profesor_materia pm ON m.id = pm.materia_id
    WHERE pm.profesor_id = ? AND pm.activo = 1 AND m.activa = 1
    ORDER BY m.especialidad, m.anio, m.nombre
");
$stmt->execute([$_SESSION['usuario_id']]);
$materias_asignadas = $stmt->fetchAll();

// Procesar subida de archivo
if ($_POST) {
    $materia_id = $_POST['materia_id'] ?? '';
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    
    if ($materia_id && $titulo && isset($_FILES['archivo'])) {
        // Verificar que la materia esté asignada al profesor
        $materia_valida = false;
        foreach ($materias_asignadas as $materia) {
            if ($materia['Id'] == $materia_id) {
                $materia_valida = true;
                break;
            }
        }
        
        if ($materia_valida) {
            $archivo = $_FILES['archivo'];
            
            if ($archivo['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                $extensiones_permitidas = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
                
                if (in_array($extension, $extensiones_permitidas)) {
                    $nombre_archivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $archivo['name']);
                    // Guardar dentro de la carpeta pública para que sea accesible por el navegador
                    $ruta_destino = '../assets/modelos_examen/' . $nombre_archivo;
                    
                    // Crear directorio si no existe (en carpeta pública)
                    if (!is_dir('../assets/modelos_examen')) {
                        mkdir('../assets/modelos_examen', 0755, true);
                    }
                    
                    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                        $stmt = $pdo->prepare("INSERT INTO modelos_examen (profesor_id, materia_id, titulo, descripcion, archivo, fecha_subida, activo) VALUES (?, ?, ?, ?, ?, NOW(), 1)");
                        if ($stmt->execute([$_SESSION['usuario_id'], $materia_id, $titulo, $descripcion, $nombre_archivo])) {
                            $success = 'Modelo de examen subido correctamente';
                        } else {
                            $error = 'Error al guardar la información en la base de datos';
                            unlink($ruta_destino); // Eliminar archivo si falla la BD
                        }
                    } else {
                        $error = 'Error al subir el archivo';
                    }
                } else {
                    $error = 'Tipo de archivo no permitido. Use: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG';
                }
            } else {
                $error = 'Error al subir el archivo';
            }
        } else {
            $error = 'No tiene permisos para subir modelos de esta materia';
        }
    } else {
        $error = 'Complete todos los campos y seleccione un archivo';
    }
}

$stmt = $pdo->prepare("
    SELECT me.*, m.nombre as materia_nombre, m.especialidad, m.anio as materia_anio
    FROM modelos_examen me
    JOIN materias m ON me.materia_id = m.id
    WHERE me.profesor_id = ? AND me.activo = 1
    ORDER BY me.fecha_subida DESC
");
$stmt->execute([$_SESSION['usuario_id']]);
$modelos_subidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Modelos de Examen - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/subir_modelo_examen.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="header">
                <h1>Subir Modelos de Examen</h1>
                <p>Comparte modelos de examen con tus estudiantes</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="upload-section">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7,10 12,15 17,10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Subir Nuevo Modelo
                </h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="materia_id">Materia:</label>
                        <select name="materia_id" id="materia_id" required>
                            <option value="">Seleccionar materia</option>
                            <?php foreach ($materias_asignadas as $materia): ?>
                                <option value="<?php echo $materia['Id']; ?>">
                                    <?php echo $materia['anio']; ?>° - <?php echo htmlspecialchars($materia['nombre']); ?> 
                                    (<?php echo htmlspecialchars($materia['especialidad']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="titulo">Título del Modelo:</label>
                        <input type="text" name="titulo" id="titulo" required placeholder="Ej: Modelo de Examen Parcial - Primer Trimestre">
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción (opcional):</label>
                        <textarea name="descripcion" id="descripcion" placeholder="Descripción del contenido del examen, temas incluidos, etc."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="archivo">Archivo:</label>
                        <input type="file" name="archivo" id="archivo" required accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                        <small>Formatos permitidos: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Subir Modelo
                    </button>
                </form>
            </div>
            
            <h3>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10,9 9,9 8,9"/>
                </svg>
                Mis Modelos Subidos
            </h3>
            <?php if (empty($modelos_subidos)): ?>
                <p>No has subido ningún modelo de examen aún.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Materia</th>
                            <th>Especialidad</th>
                            <th>Año</th>
                            <th>Fecha de Subida</th>
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modelos_subidos as $modelo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($modelo['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($modelo['materia_nombre']); ?></td>
                            <td>
                                <span class="especialidad-badge especialidad-<?php echo strtolower(str_replace(' ', '-', $modelo['especialidad'])); ?>">
                                    <?php echo htmlspecialchars($modelo['especialidad']); ?>
                                </span>
                            </td>
                            <td><?php echo $modelo['materia_anio']; ?>°</td>
                            <td><?php echo date('d/m/Y H:i', strtotime($modelo['fecha_subida'])); ?></td>
                            <td>
                                <a href="../assets/modelos_examen/<?php echo htmlspecialchars($modelo['archivo']); ?>" target="_blank" class="btn">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Ver Archivo
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
</body>
</html>

