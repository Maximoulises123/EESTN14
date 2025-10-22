<?php
// Redirigir según el tipo de usuario
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$tipo = $_SESSION['tipo'] ?? '';

switch ($tipo) {
    case 'director':
        header('Location: admin_panel.php');
        break;
    case 'profesor':
        header('Location: profesor_panel.php');
        break;
    case 'alumno':
        header('Location: alumno_panel.php');
        break;
    default:
        header('Location: perfil.php');
        break;
}
exit();
?>
