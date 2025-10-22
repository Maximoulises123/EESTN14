<?php
require_once '../src/config.php';

// Verificar que sea un profesor logueado
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'profesor') {
    header('Location: ../auth/login.php');
    exit;
}

$profesor_id = $_SESSION['usuario_id'];

// Obtener información del profesor
$stmt = $pdo->prepare("SELECT * FROM profesores WHERE id = ?");
$stmt->execute([$profesor_id]);
$profesor = $stmt->fetch();

// Obtener materias asignadas al profesor
$stmt = $pdo->prepare("
    SELECT m.*, pm.anio_academico
    FROM materias m
    JOIN profesor_materia pm ON m.id = pm.materia_id
    WHERE pm.profesor_id = ? AND pm.activo = 1 AND m.activa = 1
    ORDER BY m.anio, m.nombre
");
$stmt->execute([$profesor_id]);
$materias_asignadas = $stmt->fetchAll();

// Obtener modelos de examen subidos por el profesor
$stmt = $pdo->prepare("
    SELECT me.*, m.nombre as materia_nombre
    FROM modelos_examen me
    JOIN materias m ON me.materia_id = m.id
    WHERE me.profesor_id = ? AND me.activo = 1
    ORDER BY me.fecha_subida DESC
");
$stmt->execute([$profesor_id]);
$modelos_subidos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Profesor - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/profesor_panel.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="back-link">
                <a href="../index.php">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Volver al Inicio
                </a>
            </div>
            
            <div class="welcome-card">
                <h2>¡Bienvenido, Prof. <?php echo htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre']); ?>!</h2>
                <p>Panel de gestión de materias y modelos de examen</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($materias_asignadas); ?></div>
                    <div class="stat-label">Materias Asignadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($modelos_subidos); ?></div>
                    <div class="stat-label">Modelos Subidos</div>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        Mis Materias
                    </h3>
                    
                    <?php if (empty($materias_asignadas)): ?>
                        <div class="no-content">
                            <p>No tienes materias asignadas actualmente.</p>
                            <p>Contacta al director para que te asigne materias.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($materias_asignadas as $materia): ?>
                        <div class="materia-item">
                            <div class="materia-nombre"><?php echo htmlspecialchars($materia['nombre']); ?></div>
                            <div class="materia-info">
                                <strong>Año:</strong> <?php echo $materia['anio']; ?>° | 
                                <strong>Tipo:</strong> <?php echo $materia['tipo']; ?> | 
                                <strong>CHS:</strong> <?php echo $materia['chs']; ?>
                            </div>
                            <div class="materia-actions">
                                <a href="../modelos/subir_modelo_examen.php?materia_id=<?php echo $materia['id']; ?>" class="btn btn-primary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7,10 12,15 17,10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Subir Modelo
                                </a>
                                <a href="../materias/materia_detalle.php?id=<?php echo $materia['id']; ?>" class="btn btn-info">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="dashboard-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10,9 9,9 8,9"/>
                        </svg>
                        Mis Modelos de Examen
                    </h3>
                    
                    <?php if (empty($modelos_subidos)): ?>
                        <div class="no-content">
                            <p>No has subido ningún modelo de examen aún.</p>
                            <p>Usa el botón "Subir Modelo" en tus materias para comenzar.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($modelos_subidos as $modelo): ?>
                        <div class="modelo-item">
                            <div class="modelo-titulo"><?php echo htmlspecialchars($modelo['titulo']); ?></div>
                            <div class="modelo-info">
                                <strong>Materia:</strong> <?php echo htmlspecialchars($modelo['materia_nombre']); ?><br>
                                <strong>Archivo:</strong> <?php echo htmlspecialchars($modelo['archivo']); ?>
                            </div>
                            <div class="modelo-fecha">
                                Subido el <?php echo date('d/m/Y H:i', strtotime($modelo['fecha_subida'])); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="dashboard-card">
                <h3>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    Acciones Rápidas
                </h3>
                <div class="materia-actions">
                    <a href="../modelos/subir_modelo_examen.php" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Subir Nuevo Modelo de Examen
                    </a>
                    <a href="../modelos/modelos_examen.php" class="btn btn-info">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10,9 9,9 8,9"/>
                        </svg>
                        Ver Todos los Modelos
                    </a>
                    <a href="perfil.php" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mi Perfil
                    </a>
                </div>
            </div>
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
</body>
</html>

