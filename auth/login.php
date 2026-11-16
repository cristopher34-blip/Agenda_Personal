<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../contactos/panel.php');
    exit;
}

require_once '../config/conexion.php';

$error  = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($correo === '' || $password === '') {
        $error = 'Por favor, complete todos los campos.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Credenciales incorrectas.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nombre, correo, password FROM usuarios WHERE correo = :correo LIMIT 1');
        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_correo'] = $usuario['correo'];

            header('Location: ../contactos/panel.php');
            exit;
        } else {
            ////////////////////////
            $error = 'Credenciales incorrectas. Verifique su correo y contrasena.';
        }
    }
}

$tituloPagina = 'Iniciar Sesion - Agenda de Contactos';
require_once '../includes/header.php';
?>

<section class="tarjeta-auth">
    <h1>Iniciar sesion</h1>
    <p class="subtitulo">Accede a tu agenda personal de contactos</p>

    <?php if (isset($_GET['registro']) && $_GET['registro'] === 'ok'): ?>
        <div class="alerta alerta-exito">Cuenta creada con exito. Ya puedes iniciar sesion.</div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
        <div class="campo">
            <label for="correo">Correo electronico</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" placeholder="ejemplo@correo.com" required>
        </div>

        <div class="campo">
            <label for="password">Contrasena</label>
            <input type="password" id="password" name="password" placeholder="Ingresa tu contrasena" required>
        </div>

        <button type="submit" class="btn btn-primario btn-bloque">Ingresar</button>
    </form>

    <p class="enlace-pie">¿No tienes una cuenta? <a href="registro.php">Registrate aqui</a></p>
</section>

<?php require_once '../includes/footer.php'; ?>
