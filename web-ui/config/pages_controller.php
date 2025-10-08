<?php

$base = BASE_URL;

if ($PAGE == 'login') {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header("Location: {$base}index.php");
    }
}

if ($PAGE != 'login' && $PAGE != 'lock_screen' && $PAGE != '404' && $PAGE != '403' && $PAGE != '500') {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === false) {
        header("Location: {$base}pages/login.php");
    }
    if ($_SESSION['lock_screen'] == true) {
        header("Location: {$base}pages/lock_screen.php");
    }
}

if ($PAGE == 'lock_screen') {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === false) {
        header("Location: {$base}pages/login.php");
    }
    if ($_SESSION['lock_screen'] === false) {
        header("Location: {$base}index.php");
    }
}


?>