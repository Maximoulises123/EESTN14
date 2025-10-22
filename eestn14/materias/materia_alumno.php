<?php
require_once '../src/config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'alumno') {
    header('Location: ../auth/login.php');
    exit;
}

$materia_id = $_GET['id'] ?? '';
$alumno_id = $_SESSION['usuario_id'];

if (!$materia_id) {
    header('Location: ../panels/alumno_panel.php');
    exit;
}

// Obtener información del alumno
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE Id = ?");
$stmt->execute([$alumno_id]);
$alumno = $stmt->fetch();

// Determinar especialidad del alumno según su división
$especialidad_alumno = '';
if ($alumno['anio'] <= 3) {
    $especialidad_alumno = 'Ciclo Basico';
} else {
    $divisionRaw = (string)($alumno['division'] ?? '');
    $divisionRaw = trim($divisionRaw);
    $divisionNumero = null;
    
    if ($divisionRaw !== '') {
        $partes = explode('°', $divisionRaw);
        if (count($partes) >= 2) {
            $divisionNumero = intval(preg_replace('/[^0-9]/', '', $partes[1]));
        }
    }
    
    if ($divisionNumero === 5 || $divisionNumero === 2) {
        $especialidad_alumno = 'Programacion';
    } elseif ($divisionNumero === 3) {
        $especialidad_alumno = 'Informatica';
    } elseif ($divisionNumero === 1 || $divisionNumero === 4) {
        $especialidad_alumno = 'Alimentos';
    }
}

$stmt = $pdo->prepare("SELECT * FROM materias WHERE Id = ? AND año = ? AND especialidad = ? AND activa = 1");
$stmt->execute([$materia_id, $alumno['anio'], $especialidad_alumno]);
$materia = $stmt->fetch();

if (!$materia) {
    header('Location: ../panels/alumno_panel.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, pm.anio_academico
    FROM profesores p
    JOIN profesor_materia pm ON p.id = pm.profesor_id
    WHERE pm.materia_id = ? AND pm.activo = 1 AND p.activo = 1
    ORDER BY p.apellido, p.nombre
");
$stmt->execute([$materia_id]);
$profesores = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT me.*, p.nombre as profesor_nombre, p.apellido as profesor_apellido
    FROM modelos_examen me
    JOIN profesores p ON me.profesor_id = p.id
    WHERE me.materia_id = ? AND me.activo = 1
    ORDER BY me.fecha_subida DESC
");
$stmt->execute([$materia_id]);
$modelos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($materia['nombre']); ?> - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="back-link">
                <a href="../panels/alumno_panel.php">← Volver a Mi Curso</a>
            </div>
            
            <div class="materia-info">
                <div class="materia-title">
                    <?php echo htmlspecialchars($materia['nombre']); ?>
                </div>
                
                <div class="materia-details">
                    <div class="detail-item">
                        <div class="detail-label">Año</div>
                        <div class="detail-value"><?php echo $materia['anio']; ?>° Año</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Especialidad</div>
                        <div class="detail-value">
                            <span class="especialidad-badge">
                                <?php echo htmlspecialchars($materia['especialidad']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Categoría</div>
                        <div class="detail-value"><?php echo htmlspecialchars($materia['categoria']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Tipo</div>
                        <div class="detail-value">
                            <span class="tipo-badge tipo-<?php echo strtolower($materia['tipo']); ?>">
                                <?php echo htmlspecialchars($materia['tipo']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Carga Horaria Total</div>
                        <div class="detail-value"><?php echo $materia['cht']; ?> CHT</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Carga Horaria Semanal</div>
                        <div class="detail-value"><?php echo $materia['chs']; ?> CHS</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">👨‍🏫 Profesores Asignados</div>
                
                <?php if (empty($profesores)): ?>
                    <div class="no-content">
                        <p>No hay profesores asignados a esta materia actualmente.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($profesores as $profesor): ?>
                        <div class="profesor-card">
                            <div class="profesor-nombre">
                                <?php echo htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre']); ?>
                            </div>
                            <div class="profesor-info">
                                <strong>Email:</strong> <?php echo htmlspecialchars($profesor['email']); ?><br>
                                <?php if ($profesor['telefono']): ?>
                                    <strong>Teléfono:</strong> <?php echo htmlspecialchars($profesor['telefono']); ?><br>
                                <?php endif; ?>
                                <strong>Año Académico:</strong> <?php echo $profesor['anio_academico']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <div class="section-title">📋 Modelos de Examen</div>
                
                <?php if (empty($modelos)): ?>
                    <div class="no-content">
                        <p>No hay modelos de examen disponibles para esta materia.</p>
                        <p>Los profesores pueden subir modelos de examen desde su panel.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($modelos as $modelo): ?>
                        <div class="modelo-card">
                            <div class="modelo-header">
                                <h3 class="modelo-titulo">
                                    <?php echo htmlspecialchars($modelo['titulo']); ?>
                                </h3>
                                <span class="modelo-fecha">
                                    <?php echo date('d/m/Y H:i', strtotime($modelo['fecha_subida'])); ?>
                                </span>
                            </div>
                            
                            <div class="modelo-info">
                                <p><strong>Profesor:</strong> <?php echo htmlspecialchars($modelo['profesor_apellido'] . ', ' . $modelo['profesor_nombre']); ?></p>
                                <p><strong>Archivo:</strong> <?php echo htmlspecialchars($modelo['archivo']); ?></p>
                            </div>
                            
                            <?php if ($modelo['descripcion']): ?>
                                <div class="modelo-descripcion">
                                    <strong>Descripción:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($modelo['descripcion'])); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="modelo-actions">
                                <a href="../assets/modelos_examen/<?php echo htmlspecialchars($modelo['archivo']); ?>" 
                                   target="_blank" 
                                   class="btn btn-success">
                                    📄 Ver Modelo de Examen
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
