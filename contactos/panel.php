<?php
require_once '../includes/verificar_sesion.php';
require_once '../config/conexion.php';

$idUsuario = $_SESSION['usuario_id'];

$stmt = $pdo->prepare(
    'SELECT id, nombre, telefono, correo, ciudad
     FROM contactos
     WHERE id_usuario = :id_usuario
     ORDER BY id DESC'
);
$stmt->execute(['id_usuario' => $idUsuario]);
$contactos = $stmt->fetchAll();

$tituloPagina = 'Panel Principal - Mis Contactos';
require_once '../includes/header.php';
?>

<section class="panel-tarjeta">
    <div class="perfil-resumen">
        <div>
            <h1>Panel Principal</h1>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($_SESSION['usuario_correo']) ?></p>
        </div>
        <a href="nuevo.php" class="btn btn-primario">+ Nuevo contacto</a>
    </div>

    <?php if (isset($_GET['estado'])): ?>
        <?php if ($_GET['estado'] === 'creado'): ?>
            <div class="alerta alerta-exito">Contacto registrado correctamente.</div>
        <?php elseif ($_GET['estado'] === 'editado'): ?>
            <div class="alerta alerta-exito">Contacto actualizado correctamente.</div>
        <?php elseif ($_GET['estado'] === 'eliminado'): ?>
            <div class="alerta alerta-exito">Contacto eliminado correctamente.</div>
        <?php elseif ($_GET['estado'] === 'denegado'): ?>
            <div class="alerta alerta-error">Acceso denegado: el contacto no existe o no pertenece a tu cuenta.</div>
        <?php endif; ?>
    <?php endif; ?>

    <h2 class="titulo-seccion">Mis contactos registrados (<?= count($contactos) ?>)</h2>

    <?php if (empty($contactos)): ?>
        <div class="vacio">No tienes contactos registrados todavia. Haz clic en "+ Nuevo contacto" para agregar uno.</div>
    <?php else: ?>
        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Telefono</th>
                        <th>Correo electronico</th>
                        <th>Ciudad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos as $contacto): ?>
                        <tr>
                            <td><?= htmlspecialchars($contacto['nombre']) ?></td>
                            <td><?= htmlspecialchars($contacto['telefono']) ?></td>
                            <td><?= htmlspecialchars($contacto['correo']) ?></td>
                            <td><?= htmlspecialchars($contacto['ciudad']) ?></td>
                            <td class="acciones">
                                <a href="editar.php?id=<?= (int)$contacto['id'] ?>" class="btn btn-editar btn-sm">Editar</a>
                                <form method="POST" action="eliminar.php" class="form-inline"
                                      onsubmit="return confirmarEliminacion('<?= htmlspecialchars(addslashes($contacto['nombre'])) ?>');">
                                    <input type="hidden" name="id" value="<?= (int)$contacto['id'] ?>">
                                    <button type="submit" class="btn btn-eliminar btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once '../includes/footer.php'; ?>
