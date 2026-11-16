<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$tituloPagina = $tituloPagina ?? 'Agenda Personal de Contactos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?></title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
<?php if (isset($_SESSION['usuario_id'])): ?>
<nav class="navbar">
    <div class="navbar-contenido">
        <a href="../contactos/panel.php" class="navbar-marca">Agenda Personal</a>
        <div class="navbar-usuario">
            <span><?= htmlspecialchars($_SESSION['usuario_nombre']) ?> (<?= htmlspecialchars($_SESSION['usuario_correo']) ?>)</span>
            <a href="../auth/logout.php" class="btn btn-secundario btn-sm">Cerrar sesion</a>
        </div>
    </div>
</nav>
<?php endif; ?>
<main class="contenedor">
