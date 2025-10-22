<?php
require_once '../src/config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$usuario_tipo = $_SESSION['tipo'] ?? '';
$usuario_id = $_SESSION['usuario_id'];
$materias_usuario = [];

if ($usuario_tipo === 'alumno') {
    $stmt = $pdo->prepare("
        SELECT m.*, am.anio_academico
        FROM materias m
        JOIN alumno_materia am ON m.id = am.materia_id
        WHERE am.alumno_id = ? AND am.activo = 1 AND m.activa = 1
        ORDER BY m.especialidad, m.anio, m.nombre
    ");
    $stmt->execute([$usuario_id]);
    $materias_usuario = $stmt->fetchAll();
    
    if (empty($materias_usuario)) {
        // Si no hay asignaciones, obtener por año/especialidad del alumno
        $stmtAlumno = $pdo->prepare("SELECT anio, division FROM alumnos WHERE id = ?");
        $stmtAlumno->execute([$usuario_id]);
        $alumno = $stmtAlumno->fetch();
        
        if ($alumno) {
            $especialidad = 'Ciclo Basico';
            
            if ($alumno['anio'] > 3) {
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
                    $especialidad = 'Programacion';
                } elseif ($divisionNumero === 3) {
                    $especialidad = 'Informatica';
                } elseif ($divisionNumero === 1 || $divisionNumero === 4) {
                    $especialidad = 'Alimentos';
                }
            }
            
            $stmt = $pdo->prepare("SELECT * FROM materias WHERE anio = ? AND especialidad = ? AND activa = 1 ORDER BY nombre");
            $stmt->execute([$alumno['anio'], $especialidad]);
            $materias_usuario = $stmt->fetchAll();
        }
    }
} elseif ($usuario_tipo === 'profesor') {
    $stmt = $pdo->prepare("
        SELECT m.*, pm.anio_academico
        FROM materias m
        JOIN profesor_materia pm ON m.id = pm.materia_id
        WHERE pm.profesor_id = ? AND pm.activo = 1 AND m.activa = 1
        ORDER BY m.especialidad, m.anio, m.nombre
    ");
    $stmt->execute([$usuario_id]);
    $materias_usuario = $stmt->fetchAll();
} elseif ($usuario_tipo === 'director') {
    $stmt = $pdo->query("
        SELECT DISTINCT m.*
        FROM materias m
        WHERE m.activa = 1
        ORDER BY m.especialidad, m.anio, m.nombre
    ");
    $materias_usuario = $stmt->fetchAll();
}

$modelos = [];

if (!empty($materias_usuario)) {
    $materia_ids = array_column($materias_usuario, 'id');
    $placeholders = str_repeat('?,', count($materia_ids) - 1) . '?';
    
    $stmt = $pdo->prepare("
        SELECT me.*, m.nombre as materia_nombre, m.especialidad, m.anio as materia_anio,
               p.nombre as profesor_nombre, p.apellido as profesor_apellido
        FROM modelos_examen me
        JOIN materias m ON me.materia_id = m.id
        JOIN profesores p ON me.profesor_id = p.id
        WHERE me.materia_id IN ($placeholders) AND me.activo = 1
        ORDER BY m.especialidad, m.anio, m.nombre, me.fecha_subida DESC
    ");
    $stmt->execute($materia_ids);
    $modelos = $stmt->fetchAll();
}

// Filtrar por especialidad si se especifica
$especialidad_filtro = $_GET['especialidad'] ?? '';
if ($especialidad_filtro) {
    $modelos = array_filter($modelos, function($modelo) use ($especialidad_filtro) {
        return $modelo['especialidad'] === $especialidad_filtro;
    });
}

// Obtener especialidades únicas para el filtro
$especialidades = array_unique(array_column($modelos, 'especialidad'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelos de Examen - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/modelos_examen.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="header">
                <h1>Modelos de Examen</h1>
                <p>Consulta los modelos de examen disponibles para tus materias</p>
            </div>
            
            <?php if (!empty($especialidades)): ?>
                <div class="filters">
                    <div class="filter-group">
                        <label for="especialidad">Filtrar por especialidad:</label>
                        <select id="especialidad" onchange="filtrarEspecialidad()">
                            <option value="">Todas las especialidades</option>
                            <?php foreach ($especialidades as $especialidad): ?>
                                <option value="<?php echo htmlspecialchars($especialidad); ?>" 
                                        <?php echo $especialidad_filtro === $especialidad ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($especialidad); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (empty($modelos)): ?>
                <div class="no-models">
                    <h3>No hay modelos de examen disponibles</h3>
                    <p>No se encontraron modelos de examen para tus materias asignadas.</p>
                </div>
            <?php else: ?>
                <?php foreach ($modelos as $modelo): ?>
                    <div class="model-card">
                        <div class="model-header">
                            <h3 class="model-title">
                                <?php echo htmlspecialchars($modelo['titulo']); ?>
                            </h3>
                            <span class="model-date">
                                <?php echo date('d/m/Y H:i', strtotime($modelo['fecha_subida'])); ?>
                            </span>
                        </div>
                        
                        <div class="model-info">
                            <p><strong>Materia:</strong> <?php echo htmlspecialchars($modelo['materia_nombre']); ?></p>
                            <p>
                                <strong>Especialidad:</strong> 
                                <span class="especialidad-badge especialidad-<?php echo strtolower(str_replace(' ', '-', $modelo['especialidad'])); ?>">
                                    <?php echo htmlspecialchars($modelo['especialidad']); ?>
                                </span>
                            </p>
                            <p><strong>Año:</strong> <?php echo $modelo['materia_anio']; ?>°</p>
                            <p><strong>Profesor:</strong> <?php echo htmlspecialchars($modelo['profesor_apellido'] . ', ' . $modelo['profesor_nombre']); ?></p>
                        </div>
                        
                        <?php if ($modelo['descripcion']): ?>
                            <div class="model-description">
                                <strong>Descripción:</strong><br>
                                <?php echo nl2br(htmlspecialchars($modelo['descripcion'])); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="model-actions">
                            <a href="../assets/modelos_examen/<?php echo htmlspecialchars($modelo['archivo']); ?>" 
                               target="_blank" 
                               class="btn btn-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14,2 14,8 20,8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10,9 9,9 8,9"/>
                                </svg>
                                Ver Modelo de Examen
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
        function filtrarEspecialidad() {
            const especialidad = document.getElementById('especialidad').value;
            const url = new URL(window.location);
            
            if (especialidad) {
                url.searchParams.set('especialidad', especialidad);
            } else {
                url.searchParams.delete('especialidad');
            }
            
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
