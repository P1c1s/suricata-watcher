<?php

if ($PAGE == 'login') {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header("Location: index.php");
    }
}

if ($PAGE == 'index') {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === false) {
        header("Location: login.php");
    }
    if ($_SESSION['lock_screen'] == true) {
        header("Location: lock_screen.php");
    }
}

if ($PAGE == 'lock_screen') {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === false) {
        header("Location: login.php");
    }
    if ($_SESSION['lock_screen'] === false) {
        header("Location: index.php");
    }
}


?>