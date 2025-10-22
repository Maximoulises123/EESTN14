<?php
require_once '../src/config.php';

// Obtener materias de alimentos
$stmt = $pdo->query("
    SELECT * FROM materias 
    WHERE especialidad = 'Alimentos' AND activa = 1 
    ORDER BY año, categoria, tipo, nombre
");
$materias = $stmt->fetchAll();

// Agrupar materias por año
$materias_por_año = [];
foreach ($materias as $materia) {
    $materias_por_año[$materia['anio']][] = $materia;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias de Alimentos - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="header">
                <h1>Técnico en Tecnología de los Alimentos</h1>
                <p>Plan de estudios del Ciclo Superior - Especialidad Alimentos</p>
            </div>
            
            <div class="back-link">
                <a href="../index.php">← Volver al Inicio</a>
            </div>
            
            <?php foreach ($materias_por_año as $año => $materias_año): ?>
            <div class="year-section">
                <div class="year-title"><?php echo $año; ?>° Año</div>
                
                <?php
                // Agrupar por categoría
                $materias_por_categoria = [];
                foreach ($materias_año as $materia) {
                    $materias_por_categoria[$materia['categoria']][] = $materia;
                }
                
                foreach ($materias_por_categoria as $categoria => $materias_categoria):
                ?>
                <div class="category-section">
                    <div class="category-title"><?php echo $categoria; ?></div>
                    
                    <?php
                    // Agrupar por tipo
                    $materias_por_tipo = [];
                    foreach ($materias_categoria as $materia) {
                        $materias_por_tipo[$materia['tipo']][] = $materia;
                    }
                    
                    foreach ($materias_por_tipo as $tipo => $materias_tipo):
                    ?>
                    <div class="type-section">
                        <div class="type-title">
                            <?php echo $tipo; ?>
                            <span class="tipo-badge tipo-<?php echo strtolower($tipo); ?>">
                                <?php echo $tipo; ?>
                            </span>
                        </div>
                        
                        <div class="materias-grid">
                            <?php foreach ($materias_tipo as $materia): ?>
                            <div class="materia-card <?php echo strtolower($materia['tipo']); ?>">
                                <div class="materia-nombre">
                                    <?php echo htmlspecialchars($materia['nombre']); ?>
                                </div>
                                <div class="materia-info">
                                    <strong>CHT:</strong> <?php echo $materia['cht']; ?> | 
                                    <strong>CHS:</strong> <?php echo $materia['chs']; ?>
                                </div>
                                <div class="materia-info">
                                    <strong>Categoría:</strong> <?php echo $materia['categoria']; ?>
                                </div>
                                <a href="materia_detalle.php?id=<?php echo $materia['Id']; ?>" class="materia-link">
                                    Ver Detalles y Modelos
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
</body>
</html>

