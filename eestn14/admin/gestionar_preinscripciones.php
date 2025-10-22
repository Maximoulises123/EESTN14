<?php
require_once '../src/config.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'director')) {
    header('Location: ../auth/login.php');
    exit;
}

$mensaje = '';
if ($_POST) {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'cambiar_estado':
                $id = $_POST['id'] ?? '';
                $estado = $_POST['estado'] ?? '';
                $inscripto = isset($_POST['inscripto']) ? 1 : 0;
                
                if ($id && $estado) {
                    $stmt = $pdo->prepare("UPDATE preinscripciones SET estado = ?, inscripto = ? WHERE id = ?");
                    $stmt->execute([$estado, $inscripto, $id]);
                    $mensaje = 'Estado actualizado correctamente';
                }
                break;
                
        }
    }
}

$stmt = $pdo->query("SELECT * FROM preinscripciones ORDER BY id DESC");
$preinscripciones = $stmt->fetchAll();

$stats = [];
$stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM preinscripciones GROUP BY estado");
$stats_estado = $stmt->fetchAll();

foreach ($stats_estado as $stat) {
    $stats[$stat['estado']] = $stat['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Preinscripciones - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gestionar_preinscripciones.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="admin-container">
            <div class="admin-header">
                <h1>Gestión de Preinscripciones</h1>
                <p>Administra las preinscripciones de estudiantes</p>
            </div>
            
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <div class="admin-controls">
                <a href="../panels/admin_panel.php" class="btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Volver al Panel
                </a>
            </div>
            
            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['pendiente'] ?? 0; ?></div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['seleccionado'] ?? 0; ?></div>
                    <div class="stat-label">Seleccionados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['rechazado'] ?? 0; ?></div>
                    <div class="stat-label">Rechazados</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo array_sum($stats); ?></div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            
            
            <!-- Lista de preinscripciones -->
            <div class="section">
                <h3>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon-svg">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                        <path d="M12 11h4"/>
                        <path d="M12 16h4"/>
                        <path d="M8 11h.01"/>
                        <path d="M8 16h.01"/>
                    </svg>
                    Lista de Preinscripciones
                </h3>
                <?php if (empty($preinscripciones)): ?>
                    <p>No hay preinscripciones registradas.</p>
                <?php else: ?>
                    <table class="preinscripciones-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>DNI</th>
                                <th>Estado</th>
                                <th>Inscripto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($preinscripciones as $preinscripcion): ?>
                                <tr>
                                    <td><?php echo $preinscripcion['id']; ?></td>
                                    <td><?php echo htmlspecialchars($preinscripcion['nombre']); ?></td>
                                    <td><?php echo $preinscripcion['dni']; ?></td>
                                    <td>
                                        <span class="estado-badge estado-<?php echo $preinscripcion['estado']; ?>">
                                            <?php echo ucfirst($preinscripcion['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($preinscripcion['inscripto']): ?>
                                            <span class="estado-inscrito">✓ Sí</span>
                                        <?php else: ?>
                                            <span class="estado-no-inscrito">✗ No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="form-inline">
                                            <input type="hidden" name="accion" value="cambiar_estado">
                                            <input type="hidden" name="id" value="<?php echo $preinscripcion['id']; ?>">
                                            <select name="estado" onchange="this.form.submit()">
                                                <option value="pendiente" <?php echo $preinscripcion['estado'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="seleccionado" <?php echo $preinscripcion['estado'] === 'seleccionado' ? 'selected' : ''; ?>>Seleccionado</option>
                                                <option value="rechazado" <?php echo $preinscripcion['estado'] === 'rechazado' ? 'selected' : ''; ?>>Rechazado</option>
                                            </select>
                                            <label class="checkbox-con-margen">
                                                <input type="checkbox" name="inscripto" <?php echo $preinscripcion['inscripto'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                                Inscripto
                                            </label>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
</body>
</html>

