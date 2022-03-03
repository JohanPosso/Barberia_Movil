<?php
header('Content-Type: text/html; charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

session_name("sistema-barberia");
/* session_start();
 */
error_reporting(E_ERROR | E_PARSE );

require_once("configuracion.php");
require_once("php/db_mysql.php");
require_once("php/funciones_general.php");
require_once("php/clase_base.php");

date_default_timezone_set('America/Bogota'); /* Ajustar zona horaria a colombia */
setlocale(LC_ALL, "esm", "es_CO.utf8"); /* Configuracion regional a Español */

$db = new db_mysql();
@$db->pconnect($cfg['db_host'], $cfg['db_user'], $cfg['db_password'], $cfg['db_database_name'], $cfg['db_port']);


if ($db->connect_error()) {
    echo "<h1>Error de conexión con el servidor de base de datos.</h1>".$db->connect_error();
    exit(0);
}

$db->set_charset("utf8");

$__POST = $_POST;
$__GET = $_GET;


$_GET = array_map("escape_string", $_GET);
$_POST = array_map("escape_string", $_POST);




?>