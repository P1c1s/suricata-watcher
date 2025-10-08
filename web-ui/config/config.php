<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Path assoluto sul filesystem
define('ROOT_PATH', __DIR__ . '/../');

// URL base per front-end (da adattare al dominio)
define('BASE_URL', 'https://suricata.watcher.local/');

// Configurazione utente
$username = "admin";
$password = "admin";
$pin = 1234;

// Define page for page_controller
$PAGE = basename($_SERVER['PHP_SELF'], '.php');

session_start();

include_once ROOT_PATH . 'config/pages_controller.php';
include_once ROOT_PATH . 'config/auth_controller.php';
?>
