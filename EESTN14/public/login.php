<?php
require_once '../src/config.php';

$error = '';

if ($_POST) {
    $email = $_POST['email'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    
    if ($email && $contraseña) {
        // Buscar en la tabla usuarios (tanto usuarios normales como administradores)
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND contraseña = ?");
        $stmt->execute([$email, $contraseña]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['Id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = $usuario['tipo'] ?? 'usuario';
            
            // Redirigir según el tipo de usuario
            if ($usuario['tipo'] === 'director' || $usuario['tipo'] === 'admin') {
                header('Location: admin_panel.php');
            } else {
                header('Location: perfil.php');
            }
            exit;
        } else {
            $error = 'Credenciales incorrectas';
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
    <title>Login - EEST14</title>
    <link rel="stylesheet" href="assets/css/encabezado.css">
<body>
    <div class="login-container">
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email o Cargo:</label>
                <input type="text" name="email" id="email" required placeholder="Email para usuarios o cargo para administradores">
            </div>
            
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" required>
            </div>
            
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
        
        <div class="back-link">
            <a href="index.php">← Volver al inicio</a> | 
            <a href="registro.php">Crear cuenta</a>
        </div>
    </div>
</body>
</html>
