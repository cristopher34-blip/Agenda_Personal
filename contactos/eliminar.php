<?php
require_once '../includes/verificar_sesion.php';
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panel.php?estado=denegado');
    exit;
}

$idContacto = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$idUsuario  = $_SESSION['usuario_id'];

if (!$idContacto) {
    header('Location: panel.php?estado=denegado');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM contactos WHERE id = :id AND id_usuario = :id_usuario');
$stmt->execute([
    'id'         => $idContacto,
    'id_usuario' => $idUsuario
]);

if ($stmt->rowCount() > 0) {
    header('Location: panel.php?estado=eliminado');
} else {
    header('Location: panel.php?estado=denegado');
}
exit;
