<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = "admin";
$password = "admin";
$pin = 1234;

$PAGE = basename($_SERVER['PHP_SELF'], '.php');

session_start();

include_once 'pages_controller.php';
include_once 'auth_controller.php';

?>
