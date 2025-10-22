<?php
require_once '../src/config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'alumno') {
    header('Location: ../auth/login.php');
    exit;
}

$alumno_id = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
$stmt->execute([$alumno_id]);
$alumno = $stmt->fetch();
$especialidad_alumno = '';
if ($alumno['anio'] <= 3) {
    $especialidad_alumno = '';
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

if ($alumno['anio'] <= 3) {
    $stmt = $pdo->prepare("SELECT * FROM materias WHERE anio = ? AND especialidad = 'Ciclo Basico' AND activa = 1 ORDER BY categoria, tipo, nombre");
    $stmt->execute([$alumno['anio']]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM materias WHERE anio = ? AND especialidad = ? AND activa = 1 ORDER BY categoria, tipo, nombre");
    $stmt->execute([$alumno['anio'], $especialidad_alumno]);
}
$materias_alumno = $stmt->fetchAll();

if ($alumno['anio'] <= 3) {
    $stmt = $pdo->prepare("
        SELECT me.*, m.nombre as materia_nombre, m.especialidad, m.tipo, 
               p.nombre as profesor_nombre, p.apellido as profesor_apellido
        FROM modelos_examen me
        JOIN materias m ON me.materia_id = m.id
        JOIN profesores p ON me.profesor_id = p.id
        WHERE m.anio = ? AND m.especialidad = 'Ciclo Basico' AND me.activo = 1
        ORDER BY m.nombre, me.fecha_subida DESC
    ");
    $stmt->execute([$alumno['anio']]);
} else {
    $stmt = $pdo->prepare("
        SELECT me.*, m.nombre as materia_nombre, m.especialidad, m.tipo,
               p.nombre as profesor_nombre, p.apellido as profesor_apellido
        FROM modelos_examen me
        JOIN materias m ON me.materia_id = m.id
        JOIN profesores p ON me.profesor_id = p.id
        WHERE m.anio = ? AND m.especialidad = ? AND me.activo = 1
        ORDER BY m.nombre, me.fecha_subida DESC
    ");
    $stmt->execute([$alumno['anio'], $especialidad_alumno]);
}
$modelos_examen = $stmt->fetchAll();

$materias_por_especialidad = [];
foreach ($materias_alumno as $materia) {
    $materias_por_especialidad[$materia['especialidad']][] = $materia;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Curso - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/alumno_panel.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="welcome-card">
                <h2>¡Bienvenido, <?php echo htmlspecialchars($alumno['apellido'] . ', ' . $alumno['nombre']); ?>!</h2>
                <p><?php echo $alumno['anio']; ?>° Año - División <?php echo htmlspecialchars($alumno['division']); ?></p>
                
                <?php if ($alumno['anio'] > 3): ?>
                    <p>
                        <strong>Especialidad:</strong> 
                        <?php 
                        if ($especialidad_alumno === 'Programacion') echo 'Programación';
                        elseif ($especialidad_alumno === 'Informatica') echo 'Informática';
                        elseif ($especialidad_alumno === 'Alimentos') echo 'Alimentos';
                        ?>
                    </p>
                <?php else: ?>
                    <p><strong>Ciclo Básico</strong></p>
                <?php endif; ?>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($materias_alumno); ?></div>
                    <div class="stat-label">Materias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($modelos_examen); ?></div>
                    <div class="stat-label">Modelos Disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($materias_por_especialidad); ?></div>
                    <div class="stat-label">Especialidades</div>
                </div>
            </div>
            
            <?php foreach ($materias_por_especialidad as $especialidad => $materias): ?>
                <div class="especialidad-section">
                    <div class="especialidad-title">
                        <span class="especialidad-badge especialidad-<?php echo strtolower(str_replace(' ', '-', $especialidad)); ?>">
                            <?php echo htmlspecialchars($especialidad); ?>
                        </span>
                        Especialidad
                    </div>
                    
                    <div class="materias-grid">
                        <?php foreach ($materias as $materia): ?>
                            <div class="materia-card <?php echo strtolower($materia['tipo']); ?>">
                                <div class="materia-nombre">
                                    <?php echo htmlspecialchars($materia['nombre']); ?>
                                </div>
                                
                                <div class="materia-info">
                                    <strong>Categoría:</strong> <?php echo htmlspecialchars($materia['categoria']); ?><br>
                                    <strong>CHT:</strong> <?php echo $materia['cht']; ?> | <strong>CHS:</strong> <?php echo $materia['chs']; ?>
                                </div>
                                
                                <div class="materia-badges">
                                    <span class="badge badge-tipo <?php echo strtolower($materia['tipo']); ?>">
                                        <?php echo htmlspecialchars($materia['tipo']); ?>
                                    </span>
                                    <span class="badge badge-categoria">
                                        <?php echo htmlspecialchars($materia['categoria']); ?>
                                    </span>
                                </div>
                                
                                <div class="materia-actions">
                                    <a href="../materias/materia_alumno.php?id=<?php echo $materia['Id']; ?>" 
                                       class="btn btn-success">
                                        📚 Ver Materia y Modelos
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (!empty($modelos_examen)): ?>
                <div class="especialidad-section">
                    <div class="especialidad-title">📋 Modelos de Examen Recientes</div>
                    
                    <div class="modelos-section">
                        <?php foreach (array_slice($modelos_examen, 0, 5) as $modelo): ?>
                            <div class="modelo-item">
                                <div class="modelo-titulo">
                                    <?php echo htmlspecialchars($modelo['titulo']); ?>
                                </div>
                                
                                <div class="modelo-info">
                                    <strong>Materia:</strong> <?php echo htmlspecialchars($modelo['materia_nombre']); ?><br>
                                    <strong>Profesor:</strong> <?php echo htmlspecialchars($modelo['profesor_apellido'] . ', ' . $modelo['profesor_nombre']); ?><br>
                                    <strong>Archivo:</strong> <?php echo htmlspecialchars($modelo['archivo']); ?>
                                </div>
                                
                                <div class="modelo-fecha">
                                    Subido el <?php echo date('d/m/Y H:i', strtotime($modelo['fecha_subida'])); ?>
                                </div>
                                
                                <div class="modelo-actions">
                                    <a href="../assets/modelos_examen/<?php echo htmlspecialchars($modelo['archivo']); ?>" 
                                       target="_blank" 
                                       class="btn">
                                        📄 Ver Modelo
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
