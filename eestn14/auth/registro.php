<?php
require_once '../src/config.php';

$error = '';
$success = '';

if ($_POST) {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $año = $_POST['año'] ?? '';
    $division = trim($_POST['division'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';
    $confirmar_contraseña = $_POST['confirmar_contraseña'] ?? '';
    
    if ($nombre && $apellido && $email && $dni && $año && $contraseña && $confirmar_contraseña) {
        if ($contraseña === $confirmar_contraseña) {
            if (strlen($contraseña) >= 6) {
                $stmt = $pdo->prepare("SELECT id FROM alumnos WHERE email = ? OR dni = ?");
                $stmt->execute([$email, $dni]);
                
                if (!$stmt->fetch()) {
                    $password_hash = password_hash($contraseña, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellido, email, dni, password_hash, anio, division, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                    if ($stmt->execute([$nombre, $apellido, $email, $dni, $password_hash, $año, $division])) {
                        $success = 'Alumno registrado correctamente. Ya puedes iniciar sesión.';
                    } else {
                        $error = 'Error al crear el alumno';
                    }
                } else {
                    $error = 'El email o DNI ya está registrado';
                }
            } else {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            }
        } else {
            $error = 'Las contraseñas no coinciden';
        }
    } else {
        $error = 'Complete todos los campos obligatorios';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - EEST14</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
<body>
        <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="registro-container">
        <h2>Registro de Alumno</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($nombre ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" required value="<?php echo htmlspecialchars($apellido ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="dni">DNI:</label>
                    <input type="number" name="dni" id="dni" required value="<?php echo htmlspecialchars($dni ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="año">Año:</label>
                    <select name="año" id="año" required>
                        <option value="">Seleccionar año</option>
                        <option value="1" <?php echo ($año ?? '') == '1' ? 'selected' : ''; ?>>1° Año</option>
                        <option value="2" <?php echo ($año ?? '') == '2' ? 'selected' : ''; ?>>2° Año</option>
                        <option value="3" <?php echo ($año ?? '') == '3' ? 'selected' : ''; ?>>3° Año</option>
                        <option value="4" <?php echo ($año ?? '') == '4' ? 'selected' : ''; ?>>4° Año</option>
                        <option value="5" <?php echo ($año ?? '') == '5' ? 'selected' : ''; ?>>5° Año</option>
                        <option value="6" <?php echo ($año ?? '') == '6' ? 'selected' : ''; ?>>6° Año</option>
                        <option value="7" <?php echo ($año ?? '') == '7' ? 'selected' : ''; ?>>7° Año</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="division">División:</label>
                    <select name="division" id="division" required>
                        <option value="">Seleccione una división</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" name="contraseña" id="contraseña" required>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_contraseña">Confirmar contraseña:</label>
                    <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" required>
                </div>
            </div>
            
            <button type="submit" class="btn-registro">Registrarse</button>
        </form>
        
        </div>
    </main>
    
        <?php include '../includes/footer.php'; ?>
    
    <script>
        document.getElementById('año').addEventListener('change', function() {
            const año = this.value;
            const divisionSelect = document.getElementById('division');
            
            // Limpiar opciones existentes
            divisionSelect.innerHTML = '<option value="">Seleccione una división</option>';
            
            if (año) {
                const añoNum = parseInt(año);
                
                if (añoNum <= 3) {
                    // Ciclo básico: divisiones 1 a 6
                    for (let i = 1; i <= 6; i++) {
                        const option = document.createElement('option');
                        option.value = añoNum + '°' + i;
                        option.textContent = añoNum + '°' + i;
                        divisionSelect.appendChild(option);
                    }
                } else {
                    // Ciclo superior: divisiones 1 a 5
                    for (let i = 1; i <= 5; i++) {
                        const option = document.createElement('option');
                        option.value = añoNum + '°' + i;
                        option.textContent = añoNum + '°' + i;
                        divisionSelect.appendChild(option);
                    }
                }
            }
        });
        
        // Cargar divisiones si ya hay un año seleccionado
        const añoActual = document.getElementById('año').value;
        if (añoActual) {
            document.getElementById('año').dispatchEvent(new Event('change'));
        }
    </script>
</body>
</html>
