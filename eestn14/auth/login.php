<?php
require_once '../src/config.php';

$error = '';

if ($_POST) {
    $email = $_POST['email'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    
    if ($email && $contraseña) {
        $stmt = $pdo->prepare("SELECT * FROM directivos WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($contraseña, $usuario['password_hash'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = 'director';
            header('Location: ../panels/admin_panel.php');
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT * FROM profesores WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($contraseña, $usuario['password_hash'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = 'profesor';
            header('Location: ../panels/profesor_panel.php');
            exit;
        }
        
        $stmt = $pdo->prepare("SELECT * FROM alumnos WHERE email = ? AND activo = 1");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            if (password_verify($contraseña, $usuario['password_hash'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['tipo'] = 'alumno';
                header('Location: ../panels/alumno_panel.php');
                exit;
            } else {
                $error = 'Contraseña incorrecta para alumno';
            }
        } else {
            $error = 'Alumno no encontrado o inactivo';
        }
        
        if (!$error) {
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
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/encabezado.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
<body>
    <?php include '../includes/encabezado.php'; ?>
    
    <main>
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            
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
            
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>
