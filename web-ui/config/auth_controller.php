<?php

$base = BASE_URL;

if (!isset($_SESSION['csrf_token'])) 
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// --- CSRF check for all POST requests ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid token CSRF!");
    }

    // Credenziali di esempio (in futuro da sostituire con database)
    if (!empty($_POST["username"]) || !empty($_POST["password"]))
        if ($_POST["username"] === $username && $_POST["password"] === $password) {
            $_SESSION["logged_in"] = true;
            $_SESSION['lock_screen'] = false;
            header("Location: {$base}index.php");

            exit;
            } else {
                $error = "Wrong credentials!";
            }


    // --- Logout button clicked (index.php) ---
    if (isset($_POST["logout"])) {
        session_destroy(); 
        header("Location: {$base}pages/login.php");
    }

    // --- Lock screen triggered due to inactivity (index.php) ---
    if (isset($_POST["lock_screen"])) {
        $_SESSION["lock_screen"] = true;
        header("Location: {$base}index.php");
        exit;
    }

    // --- Check PIN input on lock_screen.php ---
    $pin_digited = $_POST["pin"] ?? "";
    
    if (!empty($pin_digited)) {
        if ($pin_digited == $pin) { // $pin deve essere definito prima
            $_SESSION["lock_screen"] = false;
            header("Location: {$base}index.php");
            exit;
        } else {
            $error = "Wrong pin!";
        }
    }
}

?>