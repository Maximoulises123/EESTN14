<?php
require_once '../src/config.php';

$error = '';
$success = '';

if ($_POST) {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contraseña = $_POST['contraseña'] ?? '';
    $confirmar_contraseña = $_POST['confirmar_contraseña'] ?? '';
    
    if ($nombre && $email && $contraseña && $confirmar_contraseña) {
        if ($contraseña === $confirmar_contraseña) {
            if (strlen($contraseña) >= 6) {
                // Verificar si el email ya existe
                $stmt = $pdo->prepare("SELECT Id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                
                if (!$stmt->fetch()) {
                    // Crear usuario
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contraseña, fecha_registro) VALUES (?, ?, ?, NOW())");
                    if ($stmt->execute([$nombre, $email, $contraseña])) {
                        $success = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
                    } else {
                        $error = 'Error al crear el usuario';
                    }
                } else {
                    $error = 'El email ya está registrado';
                }
            } else {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            }
        } else {
            $error = 'Las contraseñas no coinciden';
        }
    } else {
        $error = 'Complete todos los campos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - EEST14</title>
    <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>
    <header>
    </header>
    
    <main>
        <div class="registro-container">
        <h2>Registro de Usuario</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre completo:</label>
                <input type="text" name="nombre" id="nombre" required value="<?php echo htmlspecialchars($nombre ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" required>
            </div>
            
            <div class="form-group">
                <label for="confirmar_contraseña">Confirmar contraseña:</label>
                <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" required>
            </div>
            
            <button type="submit" class="btn-registro">Registrarse</button>
        </form>
        
        <div class="back-link">
            <a href="index.php">← Volver al inicio</a> | 
            <a href="login.php">Ya tengo cuenta</a>
        </div>
        </div>
    </main>
    
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>E.E.S.T. N°14</h3>
                    <p>Formando técnicos competentes y ciudadanos responsables desde 1980. Excelencia educativa para el futuro de Argentina.</p>
                    <div class="contact-info">
                        <div class="contact-item">Bariloche 6615, B1758 González Catán, Provincia de Buenos Aires</div>
                        <div class="contact-item"><a href="tel:02202428307">02202 42-8307</a></div>
                        <div class="contact-item"><a href="mailto:eest14platenza@abc.gob.ar">eest14platenza@abc.gob.ar</a></div>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Educación</h3>
                    <ul>
                        <li><a href="#ciclo-basico">Ciclo Básico</a></li>
                        <li><a href="#ciclo-superior">Ciclo Superior</a></li>
                        <li><a href="#tecnologia-alimentos">Tecnología en Alimentos</a></li>
                        <li><a href="#informatica">Informática</a></li>
                        <li><a href="#programacion">Programación</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="#historia">Historia</a></li>
                        <li><a href="#proposito">Propósito</a></li>
                        <li><a href="comunicados.php">Comunicados</a></li>
                        <li><a href="#proyectos">Proyectos</a></li>
                        <li><a href="preinscripcion.php">Inscripciones</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d498.53141687234967!2d-58.61777885824825!3d-34.7831965766796!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc5b3899394c5%3A0x5e91bad5b50da11d!2sEEST%20N14!5e1!3m2!1ses!2sar!4v1757653371479!5m2!1ses!2sar" 
                            width="100%" 
                            height="200"
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
            
            <div class="footer-separator"></div>
            <div class="footer-bottom">
                <p>© 2024 E.E.S.T. N°14</p>
            </div>
        </div>
    </footer>
</body>
</html>
