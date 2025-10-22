<?php
require_once '../src/config.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'director') {
    header('Location: ../auth/login.php');
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $profesor_id = $_POST['profesor_id'] ?? '';
    $materia_id = $_POST['materia_id'] ?? '';
    $accion = $_POST['accion'] ?? '';
    
    if ($profesor_id && $materia_id && $accion) {
        if ($accion === 'asignar') {
            $stmt = $pdo->prepare("SELECT id FROM profesor_materia WHERE profesor_id = ? AND materia_id = ? AND activo = 1");
            $stmt->execute([$profesor_id, $materia_id]);
            
            if (!$stmt->fetch()) {
                $stmt = $pdo->prepare("INSERT INTO profesor_materia (profesor_id, materia_id, anio_academico, activo) VALUES (?, ?, ?, 1)");
                if ($stmt->execute([$profesor_id, $materia_id, date('Y')])) {
                    $success = 'Materia asignada correctamente al profesor';
                } else {
                    $error = 'Error al asignar la materia';
                }
            } else {
                $error = 'Esta materia ya está asignada al profesor';
            }
        } elseif ($accion === 'desasignar') {
            $stmt = $pdo->prepare("UPDATE profesor_materia SET activo = 0 WHERE profesor_id = ? AND materia_id = ?");
            if ($stmt->execute([$profesor_id, $materia_id])) {
                $success = 'Materia desasignada correctamente del profesor';
            } else {
                $error = 'Error al desasignar la materia';
            }
        }
    }
}

$stmt = $pdo->query("SELECT * FROM profesores WHERE activo = 1 ORDER BY apellido, nombre");
$profesores = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM materias WHERE activa = 1 ORDER BY especialidad, anio, nombre");
$materias = $stmt->fetchAll();

$stmt = $pdo->query("
    SELECT pm.*, p.nombre as profesor_nombre, p.apellido as profesor_apellido, 
           m.nombre as materia_nombre, m.especialidad, m.anio, m.tipo
    FROM profesor_materia pm
    JOIN profesores p ON pm.profesor_id = p.id
    JOIN materias m ON pm.materia_id = m.id
    WHERE pm.activo = 1
    ORDER BY p.apellido, p.nombre, m.especialidad, m.anio, m.nombre
");
$asignaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Materias a Profesores - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/asignar_materias_profesor.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="container">
            <div class="back-link">
                <a href="../panels/admin_panel.php">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Volver al Panel de Administración
                </a>
            </div>
            
            <div class="header">
                <h1>Asignar Materias a Profesores</h1>
                <p>Gestiona las asignaciones de materias a los profesores</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="actions-section">
                <h3>Gestión de Asignaciones</h3>
                <div class="actions-grid">
                    <div class="action-form">
                        <h4>Asignar Materia</h4>
                        <form method="POST">
                            <input type="hidden" name="accion" value="asignar">
                            
                            <div class="form-group">
                                <label for="profesor_asignar">Profesor:</label>
                                <select name="profesor_id" id="profesor_asignar" required>
                                    <option value="">Seleccionar profesor</option>
                                    <?php foreach ($profesores as $profesor): ?>
                                        <option value="<?php echo $profesor['id']; ?>">
                                            <?php echo htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="materia_asignar">Materia:</label>
                                <div class="search-container">
                                    <input type="text" id="search_materia_asignar" class="search-input" placeholder="Buscar materia..." onkeyup="filtrarMaterias('asignar')">
                                    
                                    <div class="filter-buttons">
                                        <button type="button" class="filter-btn active" onclick="filtrarPorEspecialidad('asignar', '')">Todas</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('asignar', 'Programacion')">Programación</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('asignar', 'Informatica')">Informática</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('asignar', 'Alimentos')">Alimentos</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('asignar', 'Ciclo Basico')">Ciclo Básico</button>
                                    </div>
                                    
                                    <div class="materias-list" id="materias_list_asignar">
                                        <?php foreach ($materias as $materia): ?>
                                        <div class="materia-option" data-especialidad="<?php echo $materia['especialidad']; ?>" data-nombre="<?php echo strtolower($materia['nombre']); ?>" onclick="seleccionarMateria('asignar', <?php echo $materia['id']; ?>, '<?php echo htmlspecialchars($materia['nombre']); ?>')">
                                            <div class="materia-info">
                                                <div class="materia-nombre"><?php echo htmlspecialchars($materia['nombre']); ?></div>
                                                <div class="materia-details">
                                                    <?php echo $materia['anio']; ?>° Año | <?php echo $materia['categoria']; ?>
                                                </div>
                                            </div>
                                            <div class="materia-badges">
                                                <span class="badge badge-especialidad"><?php echo $materia['especialidad']; ?></span>
                                                <span class="badge badge-tipo"><?php echo $materia['tipo']; ?></span>
                                                <span class="badge badge-año"><?php echo $materia['anio']; ?>°</span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="materia_id" id="materia_id_asignar" required>
                                <div id="materia_seleccionada_asignar" class="materia-seleccionada asignar">
                                    <strong>Materia seleccionada:</strong> <span id="nombre_materia_asignar"></span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                    <line x1="16" y1="11" x2="22" y2="11"/>
                                    <line x1="19" y1="8" x2="19" y2="14"/>
                                </svg>
                                Asignar Materia
                            </button>
                        </form>
                    </div>
                    
                    <div class="action-form">
                        <h4>Desasignar Materia</h4>
                        <form method="POST" onsubmit="return confirm('¿Está seguro de desasignar esta materia?')">
                            <input type="hidden" name="accion" value="desasignar">
                            
                            <div class="form-group">
                                <label for="profesor_desasignar">Profesor:</label>
                                <select name="profesor_id" id="profesor_desasignar" required>
                                    <option value="">Seleccionar profesor</option>
                                    <?php foreach ($profesores as $profesor): ?>
                                        <option value="<?php echo $profesor['id']; ?>">
                                            <?php echo htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="materia_desasignar">Materia:</label>
                                <div class="search-container">
                                    <input type="text" id="search_materia_desasignar" class="search-input" placeholder="Buscar materia..." onkeyup="filtrarMaterias('desasignar')">
                                    
                                    <div class="filter-buttons">
                                        <button type="button" class="filter-btn active" onclick="filtrarPorEspecialidad('desasignar', '')">Todas</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('desasignar', 'Programacion')">Programación</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('desasignar', 'Informatica')">Informática</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('desasignar', 'Alimentos')">Alimentos</button>
                                        <button type="button" class="filter-btn" onclick="filtrarPorEspecialidad('desasignar', 'Ciclo Basico')">Ciclo Básico</button>
                                    </div>
                                    
                                    <div class="materias-list" id="materias_list_desasignar">
                                        <?php foreach ($materias as $materia): ?>
                                        <div class="materia-option" data-especialidad="<?php echo $materia['especialidad']; ?>" data-nombre="<?php echo strtolower($materia['nombre']); ?>" onclick="seleccionarMateria('desasignar', <?php echo $materia['id']; ?>, '<?php echo htmlspecialchars($materia['nombre']); ?>')">
                                            <div class="materia-info">
                                                <div class="materia-nombre"><?php echo htmlspecialchars($materia['nombre']); ?></div>
                                                <div class="materia-details">
                                                    <?php echo $materia['anio']; ?>° Año | <?php echo $materia['categoria']; ?>
                                                </div>
                                            </div>
                                            <div class="materia-badges">
                                                <span class="badge badge-especialidad"><?php echo $materia['especialidad']; ?></span>
                                                <span class="badge badge-tipo"><?php echo $materia['tipo']; ?></span>
                                                <span class="badge badge-año"><?php echo $materia['anio']; ?>°</span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="materia_id" id="materia_id_desasignar" required>
                                <div id="materia_seleccionada_desasignar" class="materia-seleccionada desasignar">
                                    <strong>Materia seleccionada:</strong> <span id="nombre_materia_desasignar"></span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-danger">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18M6 6l12 12"/>
                                </svg>
                                Desasignar Materia
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <h3>Asignaciones Actuales</h3>
                
                <?php if (empty($asignaciones)): ?>
                    <p>No hay asignaciones de materias actualmente.</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Profesor</th>
                                <th>Materia</th>
                                <th>Especialidad</th>
                                <th>Año</th>
                                <th>Tipo</th>
                                <th>Año Académico</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asignaciones as $asignacion): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($asignacion['profesor_apellido'] . ', ' . $asignacion['profesor_nombre']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($asignacion['materia_nombre']); ?></td>
                                <td>
                                    <span class="especialidad-badge especialidad-<?php echo strtolower(str_replace(' ', '-', $asignacion['especialidad'])); ?>">
                                        <?php echo htmlspecialchars($asignacion['especialidad']); ?>
                                    </span>
                                </td>
                                <td><?php echo $asignacion['anio']; ?>°</td>
                                <td>
                                    <span class="tipo-badge tipo-<?php echo strtolower($asignacion['tipo']); ?>">
                                        <?php echo htmlspecialchars($asignacion['tipo']); ?>
                                    </span>
                                </td>
                                <td><?php echo $asignacion['año_academico']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
    
    <script>
        let filtroEspecialidad = {
            asignar: '',
            desasignar: ''
        };
        
        function filtrarMaterias(tipo) {
            const searchInput = document.getElementById('search_materia_' + tipo);
            const searchTerm = searchInput.value.toLowerCase();
            const materiasList = document.getElementById('materias_list_' + tipo);
            const materias = materiasList.querySelectorAll('.materia-option');
            
            materias.forEach(materia => {
                const nombre = materia.getAttribute('data-nombre');
                const especialidad = materia.getAttribute('data-especialidad');
                
                const matchesSearch = nombre.includes(searchTerm);
                const matchesEspecialidad = filtroEspecialidad[tipo] === '' || especialidad === filtroEspecialidad[tipo];
                
                if (matchesSearch && matchesEspecialidad) {
                    materia.style.display = 'flex';
                } else {
                    materia.style.display = 'none';
                }
            });
        }
        
        function filtrarPorEspecialidad(tipo, especialidad) {
            filtroEspecialidad[tipo] = especialidad;
            
            const buttons = document.querySelectorAll(`#materias_list_${tipo}`).parentElement.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            filtrarMaterias(tipo);
        }
        
        function seleccionarMateria(tipo, materiaId, nombreMateria) {
            document.getElementById('materia_id_' + tipo).value = materiaId;
            const seleccionada = document.getElementById('materia_seleccionada_' + tipo);
            const nombreSpan = document.getElementById('nombre_materia_' + tipo);
            
            nombreSpan.textContent = nombreMateria;
            seleccionada.style.display = 'block';
            
            const materiasList = document.getElementById('materias_list_' + tipo);
            materiasList.style.display = 'none';
        }
        
        function limpiarSeleccion(tipo) {
            document.getElementById('materia_id_' + tipo).value = '';
            document.getElementById('materia_seleccionada_' + tipo).style.display = 'none';
            document.getElementById('materias_list_' + tipo).style.display = 'block';
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchAsignar = document.getElementById('search_materia_asignar');
            const listAsignar = document.getElementById('materias_list_asignar');
            
            searchAsignar.addEventListener('focus', function() {
                listAsignar.style.display = 'block';
            });
            
            const searchDesasignar = document.getElementById('search_materia_desasignar');
            const listDesasignar = document.getElementById('materias_list_desasignar');
            
            searchDesasignar.addEventListener('focus', function() {
                listDesasignar.style.display = 'block';
            });
            
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.search-container')) {
                    listAsignar.style.display = 'none';
                    listDesasignar.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
