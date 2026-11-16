<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../contactos/panel.php');
    exit;
}

require_once '../config/conexion.php';

$errores = [];
$nombre  = '';
$correo  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones en el servidor
    if ($nombre === '') {
        $errores[] = 'El nombre completo es obligatorio.';
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingrese un correo electronico valido.';
    }
    if (strlen($password) < 6) {
        $errores[] = 'La contrasena debe tener al menos 6 caracteres.';
    }

    if (empty($errores)) {
        /////////////////////////////////////////////////////////////
    //verificar que el correo no exita
    
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = :correo LIMIT 1');
        $stmt->execute(['correo' => $correo]);

        if ($stmt->fetch()) {
            $errores[] = 'El correo electronico ya esta registrado.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare(
                'INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)'
            );
            $insert->execute([
                'nombre'   => $nombre,
                'correo'   => $correo,
                'password' => $passwordHash
            ]);

            header('Location: login.php?registro=ok');
            exit;
        }
    }
}

$tituloPagina = 'Registro de Usuario - Agenda de Contactos';
require_once '../includes/header.php';
?>

<section class="tarjeta-auth">
    <h1>Crear cuenta</h1>
    <p class="subtitulo">Registrate para administrar tus contactos de forma privada</p>

    <?php if (!empty($errores)): ?>
        <div class="alerta alerta-error">
            <ul>
                <?php foreach ($errores as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="registro.php" novalidate>
        <div class="campo">
            <label for="nombre">Nombre completo</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" placeholder="Ej. Cristopher Arpi" required>
        </div>

        <div class="campo">
            <label for="correo">Correo electronico</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($correo) ?>" placeholder="ejemplo@correo.com" required>
        </div>

        <div class="campo">
            <label for="password">Contrasena (minimo 6 caracteres)</label>
            <input type="password" id="password" name="password" placeholder="Crea una contrasena segura" required>
        </div>

        <button type="submit" class="btn btn-primario btn-bloque">Registrarme</button>
    </form>

    <p class="enlace-pie">¿Ya tienes cuenta? <a href="login.php">Inicia sesion</a></p>
</section>

<?php require_once '../includes/footer.php'; ?>
