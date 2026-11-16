<?php
require_once '../includes/verificar_sesion.php';
require_once '../config/conexion.php';

$errores  = [];
$nombre   = '';
$telefono = '';
$correo   = '';
$ciudad   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $ciudad   = trim($_POST['ciudad'] ?? '');

    if ($nombre === '') {
        $errores[] = 'El nombre del contacto es obligatorio.';
    }
    if ($telefono === '') {
        $errores[] = 'El numero telefonico es obligatorio.';
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingrese un correo electronico valido.';
    }
    if ($ciudad === '') {
        $errores[] = 'La ciudad de residencia es obligatoria.';
    }

    if (empty($errores)) {
        $stmt = $pdo->prepare(
            'INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad)
             VALUES (:id_usuario, :nombre, :telefono, :correo, :ciudad)'
        );
        $stmt->execute([
            'id_usuario' => $_SESSION['usuario_id'],
            'nombre'     => $nombre,
            'telefono'   => $telefono,
            'correo'     => $correo,
            'ciudad'     => $ciudad
        ]);

        header('Location: panel.php?estado=creado');
        exit;
    }
}

$tituloPagina = 'Nuevo Contacto - Agenda Personal';
require_once '../includes/header.php';
?>

<section class="tarjeta-auth">
    <h1>Registrar contacto</h1>
    <p class="subtitulo">Agrega un nuevo contacto a tu agenda personal</p>

    <?php if (!empty($errores)): ?>
        <div class="alerta alerta-error">
            <ul>
                <?php foreach ($errores as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="nuevo.php" novalidate>
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
        </div>

        <div class="campo">
            <label for="telefono">Telefono</label>
            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($telefono) ?>" required>
        </div>

        <div class="campo">
            <label for="correo">Correo electronico</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" required>
        </div>

        <div class="campo">
            <label for="ciudad">Ciudad</label>
            <input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($ciudad) ?>" required>
        </div>

        <div class="botones-form">
            <button type="submit" class="btn btn-primario">Guardar contacto</button>
            <a href="panel.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</section>

<?php require_once '../includes/footer.php'; ?>
