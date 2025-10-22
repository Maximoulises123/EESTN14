<?php
require_once '../src/config.php';

// Verificar que sea un director logueado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'director') {
    header('Location: ../auth/login.php');
    exit;
}

$profesor_id = $_GET['profesor_id'] ?? '';

if (!$profesor_id) {
    header('Location: admin_panel.php');
    exit;
}

// Obtener información del profesor
$stmt = $pdo->prepare("SELECT * FROM profesores WHERE id = ?");
$stmt->execute([$profesor_id]);
$profesor = $stmt->fetch();

if (!$profesor) {
    header('Location: admin_panel.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT me.*, m.nombre as materia_nombre, m.especialidad, m.anio, m.tipo
    FROM modelos_examen me
    JOIN materias m ON me.materia_id = m.id
    WHERE me.profesor_id = ? AND me.activo = 1
    ORDER BY m.especialidad, m.anio, m.nombre, me.fecha_subida DESC
");
$stmt->execute([$profesor_id]);
$modelos = $stmt->fetchAll();

// Agrupar modelos por materia
$modelos_por_materia = [];
foreach ($modelos as $modelo) {
    $modelos_por_materia[$modelo['materia_nombre']][] = $modelo;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelos de Examen - <?php echo htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre']); ?> - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="back-link">
                <a href="gestionar_profesores.php">← Volver a Gestionar Profesores</a>
            </div>
            
            <div class="profesor-info">
                <div class="profesor-nombre">
                    Prof. <?php echo htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre']); ?>
                </div>
                <div class="profesor-details">
                    <strong>Email:</strong> <?php echo htmlspecialchars($profesor['email']); ?>
                    <?php if ($profesor['telefono']): ?>
                        | <strong>Teléfono:</strong> <?php echo htmlspecialchars($profesor['telefono']); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($modelos); ?></div>
                    <div class="stat-label">Modelos Subidos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($modelos_por_materia); ?></div>
                    <div class="stat-label">Materias con Modelos</div>
                </div>
            </div>
            
            <?php if (empty($modelos)): ?>
                <div class="materia-section">
                    <div class="no-content">
                        <p>Este profesor no ha subido ningún modelo de examen aún.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($modelos_por_materia as $materia_nombre => $modelos_materia): ?>
                    <div class="materia-section">
                        <div class="materia-title">
                            📚 <?php echo htmlspecialchars($materia_nombre); ?>
                        </div>
                        
                        <div class="materia-badges">
                            <?php $primera_materia = $modelos_materia[0]; ?>
                            <span class="badge badge-especialidad">
                                <?php echo htmlspecialchars($primera_materia['especialidad']); ?>
                            </span>
                            <span class="badge badge-tipo <?php echo strtolower($primera_materia['tipo']); ?>">
                                <?php echo htmlspecialchars($primera_materia['tipo']); ?>
                            </span>
                            <span class="badge badge-anio">
                                <?php echo $primera_materia['anio']; ?>° Año
                            </span>
                        </div>
                        
                        <?php foreach ($modelos_materia as $modelo): ?>
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
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
