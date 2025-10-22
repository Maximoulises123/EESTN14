<?php
require_once '../src/config.php';

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'director') {
    header('Location: ../auth/login.php');
    exit;
}
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    
}

$stats = [];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM comunicados");
$stats['total_comunicados'] = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM comunicados WHERE destacado = 1");
$stats['comunicados_destacados'] = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM proyectos");
$stats['total_proyectos'] = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT categoria, COUNT(*) as total FROM proyectos GROUP BY categoria");
$stats['proyectos_por_categoria'] = $stmt->fetchAll();

$stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM preinscripciones GROUP BY estado");
$stats['preinscripciones_por_estado'] = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/admin_panel.css">
</head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1>Panel de Administración</h1>
                <p>Gestión integral de comunicados, proyectos y preinscripciones</p>
            </div>
            
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['total_comunicados']; ?></div>
                    <div class="stat-label">Comunicados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['total_proyectos']; ?></div>
                    <div class="stat-label">Proyectos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['comunicados_destacados']; ?></div>
                    <div class="stat-label">Destacados</div>
                </div>
                <?php 
                $total_preinscripciones = array_sum(array_column($stats['preinscripciones_por_estado'], 'total'));
                ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_preinscripciones; ?></div>
                    <div class="stat-label">Preinscripciones</div>
                </div>
            </div>
            
            <!-- Grid de administración -->
            <div class="admin-grid">
                <!-- Gestión de Comunicados -->
                <div class="admin-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            <path d="M13 8H7"/>
                            <path d="M17 12H7"/>
                        </svg>
                        Gestión de Comunicados
                    </h3>
                    <p>Administra los comunicados oficiales de la institución</p>
                    <a href="../admin/publicar_comunicado.php" class="btn-admin">Publicar Comunicado</a>
                    <a href="../admin/editar_comunicados.php" class="btn-admin">Gestionar Comunicados</a>
                </div>
                
                <!-- Gestión de Profesores -->
                <div class="admin-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Gestión de Profesores
                    </h3>
                    <p>Administra profesores y sus asignaciones</p>
                    <a href="../admin/gestionar_profesores.php" class="btn-admin">Gestionar Profesores</a>
                    <a href="../admin/asignar_materias_profesor.php" class="btn-admin success">Asignar Materias</a>
                </div>
                
                <!-- Gestión de Proyectos -->
                <div class="admin-card">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                            <path d="M12 11h4"/>
                            <path d="M12 16h4"/>
                            <path d="M8 11h.01"/>
                            <path d="M8 16h.01"/>
                        </svg>
                        Gestión de Proyectos
                    </h3>
                    <p>Administra proyectos por categorías</p>
                    <a href="../admin/gestionar_proyectos.php" class="btn-admin">Gestionar Proyectos</a>
                    <a href="../proyectos/proyecto.php?seccion=destacados" class="btn-admin success">Proyectos Destacados</a>
                </div>
                
                <!-- Gestión de Preinscripciones -->
                <div class="admin-card destacado">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                            <path d="M12 11h4"/>
                            <path d="M12 16h4"/>
                            <path d="M8 11h.01"/>
                            <path d="M8 16h.01"/>
                        </svg>
                        Gestión de Preinscripciones
                    </h3>
                    <p>Administra las preinscripciones de estudiantes</p>
                    <a href="../admin/gestionar_preinscripciones.php" class="btn-admin">Ver Preinscripciones</a>
                </div>
                
                <!-- Estadísticas Detalladas -->
                <div class="admin-card exito">
                    <h3>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <path d="M18 20V10"/>
                            <path d="M12 20V4"/>
                            <path d="M6 20v-6"/>
                        </svg>
                        Estadísticas
                    </h3>
                    <p>Visualiza estadísticas detalladas</p>
                    <h4>Proyectos por Categoría:</h4>
                    <?php foreach ($stats['proyectos_por_categoria'] as $categoria): ?>
                        <p><strong><?php echo ucfirst($categoria['categoria']); ?>:</strong> <?php echo $categoria['total']; ?></p>
                    <?php endforeach; ?>
                    
                    <h4>Preinscripciones:</h4>
                    <?php foreach ($stats['preinscripciones_por_estado'] as $estado): ?>
                        <p><strong><?php echo ucfirst($estado['estado']); ?>:</strong> <?php echo $estado['total']; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    
    
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>

