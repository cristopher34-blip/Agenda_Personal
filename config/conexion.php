<?php
$host    = 'localhost';
$db      = 'agenda_contactos';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';
//////////////////////////////////////////////////////////////////////

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opciones);
} catch (PDOException $e) {
  /////////////////////////////////////////////////////////////////


    http_response_code(500);
    exit('Error interno de conexion con la base de datos. Verifique la configuracion del servidor.');
}
