<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: contactos/panel.php');
    exit;
}

header('Location: auth/login.php');
exit;
