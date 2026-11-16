# Agenda Personal de Contactos (PHP y MySQL)

## Descripcion del proyecto
Aplicacion web desarrollada en PHP y MySQL que permite a los usuarios registrarse, iniciar sesion y gestionar de forma privada su propia agenda de contactos (crear, consultar, editar y eliminar). El sistema implementa manejo seguro de sesiones, cifrado de contrasenas y control estricto de propiedad por usuario para impedir el acceso no autorizado a registros de terceros.

## Tecnologias utilizadas
- **Lenguaje Backend:** PHP 8.x (con extension PDO y consultas preparadas)
- **Base de datos:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 y JavaScript
- **Servidor Web:** Apache (XAMPP / LAMP)

## Requisitos para su ejecucion
- Servidor local con PHP >= 8.0 y MySQL >= 5.7 o MariaDB >= 10.4 (por ejemplo, XAMPP, Laragon o WAMP).
- Extension `pdo_mysql` habilitada.
- Navegador web moderno (Chrome, Firefox, Edge).

## Pasos para instalar la aplicacion
1. Descomprimir o clonar la carpeta `agenda_personal` dentro del directorio publico del servidor web (por ejemplo, `C:\xampp\htdocs\agenda_personal` en Windows o `/var/www/html/agenda_personal` en Linux).
2. Iniciar los modulos **Apache** y **MySQL** desde el panel de control de XAMPP.
3. Ingresar a **phpMyAdmin** (`http://localhost/phpmyadmin`) e importar el archivo `sql/agenda_contactos.sql` para crear automaticamente la base de datos `agenda_contactos` y las tablas `usuarios` y `contactos`.
4. Verificar que las credenciales en `config/conexion.php` (`$host`, `$db`, `$user`, `$pass`) coincidan con tu servidor MySQL local.
5. Abrir en el navegador la direccion `http://localhost/agenda_personal/index.php`.
