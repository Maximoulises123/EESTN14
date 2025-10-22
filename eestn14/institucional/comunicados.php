<?php
require_once '../src/config.php';

$busqueda = $_GET['busqueda'] ?? '';
$where_conditions = [];
$params = [];
$comunicados = [];

// Verificar que hay conexión a la base de datos
if ($pdo !== null) {
    try {
        if (!empty($busqueda)) {
            $where_conditions[] = "titulo LIKE ? OR descripcion LIKE ?";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }

        // Construir consulta
        $sql = "SELECT * FROM comunicados";
        if (!empty($where_conditions)) {
            $sql .= " WHERE " . implode(" AND ", $where_conditions);
        }
        $sql .= " ORDER BY fecha DESC, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $comunicados = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en comunicados.php: " . $e->getMessage());
        $comunicados = [];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicados - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/comunicados.css">
    </head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <?php if (isset($_SESSION['tipo']) && ($_SESSION['tipo'] === 'admin' || $_SESSION['tipo'] === 'director')): ?>
            <div class="admin-section">
                <a href="../panels/admin_panel.php" class="btn-admin">Panel de Administración</a>
                <a href="../admin/publicar_comunicado.php" class="btn-admin">Publicar Comunicado</a>
                <a href="../admin/editar_comunicados.php" class="btn-admin">Gestionar Comunicados</a>
            </div>
        <?php endif; ?>
        
        <!-- Buscador de Comunicados -->
        <div class="buscador-section">
            <form method="GET" class="buscador-form">
                <div class="buscador-grid">
                    <div class="buscador-item">
                        <input type="text" name="busqueda" id="busqueda" 
                               value="<?php echo htmlspecialchars($busqueda); ?>" 
                               placeholder="Ingrese texto a buscar...">
                    </div>
                    <div class="buscador-item">
                        <label>&nbsp;</label>
                        <div class="buscador-botones">
                            <button type="submit" class="btn-buscar">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                </svg>
                                Buscar
                            </button>
                            <a href="comunicados.php" class="btn-limpiar">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            
            <?php if (!empty($busqueda) || !empty($comunicado_busqueda)): ?>
                <div class="resultados-busqueda">
                    <p><strong>Resultados de búsqueda:</strong></p>
                    <?php if (!empty($busqueda)): ?>
                        <span class="filtro-activo">Texto: "<?php echo htmlspecialchars($busqueda); ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($comunicado_busqueda)): ?>
                        <span class="filtro-activo">Comunicado: <?php echo htmlspecialchars($comunicado_busqueda); ?></span>
                    <?php endif; ?>
                    <span class="total-resultados"><?php echo count($comunicados); ?> resultado(s) encontrado(s)</span>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (empty($comunicados)): ?>
            <?php if (!empty($busqueda) || !empty($comunicado_busqueda)): ?>
                <div class="sin-resultados">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        No se encontraron resultados
                    </h3>
                    <p>No hay comunicados que coincidan con tu búsqueda.</p>
                    <p>Intenta con otros términos o <a href="comunicados.php">ver todos los comunicados</a>.</p>
                </div>
            <?php else: ?>
                <div class="sin-comunicados">
                    <h3>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            <path d="M13 8H7"/>
                            <path d="M17 12H7"/>
                        </svg>
                        No hay comunicados disponibles
                    </h3>
                    <p>Los comunicados oficiales aparecerán aquí cuando estén disponibles.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="comunicados-container">
                <?php foreach ($comunicados as $comunicado): ?>
                    <div class="comunicado-item">
                        <?php if ($comunicado['imagen']): ?>
                            <img src="<?php echo htmlspecialchars('../' . $comunicado['imagen']); ?>?v=<?php echo time(); ?>" 
                                 alt="Imagen del comunicado" 
                                 class="comunicado-imagen">
                        <?php else: ?>
                            <div class="comunicado-imagen-placeholder">
                                <div class="placeholder-content">
                                    <svg class="placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21,15 16,10 5,21"/>
                                    </svg>
                                    <p>Imagen del comunicado</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="comunicado-header">
                            <div class="titulo-fecha">
                                <h3><?php echo htmlspecialchars($comunicado['titulo']); ?></h3>
                                <p class="fecha">
                                    <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($comunicado['fecha'])); ?>
                                    <?php if ($comunicado['numero'] && $comunicado['anio']): ?>
                                        | <strong>Comunicado:</strong> <?php echo $comunicado['numero'] . '/' . $comunicado['anio']; ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="badges">
                                <?php if (!empty($comunicado['importancia'])): ?>
                                    <span class="importancia-badge importancia-<?php echo strtolower($comunicado['importancia']); ?>">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                            <line x1="12" y1="9" x2="12" y2="13"/>
                                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                                        </svg>
                                        <?php echo ucfirst($comunicado['importancia']); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($comunicado['destacado']): ?>
                                    <span class="badge-destacado">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg-small">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        Destacado
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($comunicado['descripcion']): ?>
                            <div class="comunicado-content">
                                <div class="comunicado-descripcion">
                                    <?php echo nl2br(htmlspecialchars($comunicado['descripcion'])); ?>
                                </div>
                                <a href="#" class="comunicado-ver-mas">Ver más</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
