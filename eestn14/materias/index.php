<?php
// Redirigir según el tipo de usuario
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$tipo = $_SESSION['tipo'] ?? '';

switch ($tipo) {
    case 'alumno':
        header('Location: materia_alumno.php');
        break;
    default:
        header('Location: materias_ciclo_basico.php');
        break;
}
exit();
?>
