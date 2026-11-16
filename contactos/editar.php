<?php
require_once '../includes/verificar_sesion.php';
require_once '../config/conexion.php';

$idUsuario  = $_SESSION['usuario_id'];
$idContacto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idContacto) {
    header('Location: panel.php?estado=denegado');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT id, nombre, telefono, correo, ciudad
     FROM contactos
     WHERE id = :id AND id_usuario = :id_usuario
     LIMIT 1'
);
$stmt->execute([
    'id'         => $idContacto,
    'id_usuario' => $idUsuario
]);
$contacto = $stmt->fetch();

if (!$contacto) {
    header('Location: panel.php?estado=denegado');
    exit;
}

$errores  = [];
$nombre   = $contacto['nombre'];
$telefono = $contacto['telefono'];
$correo   = $contacto['correo'];
$ciudad   = $contacto['ciudad'];

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
        $update = $pdo->prepare(
            'UPDATE contactos
             SET nombre = :nombre, telefono = :telefono, correo = :correo, ciudad = :ciudad
             WHERE id = :id AND id_usuario = :id_usuario'
        );
        $update->execute([
            'nombre'     => $nombre,
            'telefono'   => $telefono,
            'correo'     => $correo,
            'ciudad'     => $ciudad,
            'id'         => $idContacto,
            'id_usuario' => $idUsuario
        ]);

        header('Location: panel.php?estado=editado');
        exit;
    }
}

$tituloPagina = 'Editar Contacto - Agenda Personal';
require_once '../includes/header.php';
?>

<section class="tarjeta-auth">
    <h1>Editar contacto</h1>
    <p class="subtitulo">Modifica los datos de tu contacto seleccionado</p>

    <?php if (!empty($errores)): ?>
        <div class="alerta alerta-error">
            <ul>
                <?php foreach ($errores as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="editar.php?id=<?= (int)$idContacto ?>" novalidate>
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
            <button type="submit" class="btn btn-primario">Actualizar contacto</button>
            <a href="panel.php" class="btn btn-secundario">Cancelar</a>
        </div>
    </form>
</section>

<?php require_once '../includes/footer.php'; ?>
