<?php
include_once 'config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'head.php'; ?>
  <title>Suricata Watcher | Lock screen</title>
</head>

<body>

<div class="login-box">
    <!-- Logo sopra il form -->
    <img class="form-logo" src="suricata.svg" alt="Suricata logo">

    <div class="form-container">
        <form method="post" class="form-card">
            <!-- Titolo dentro il form -->
            <p class="form-title">Login</p>

            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <input type="text" name="username" placeholder="Username" required class="form-control">
            <input type="password" name="password" placeholder="Password" required class="form-control">
            
            <button type="submit" class="btn btn-gray">Login</button>
        </form>
        <?php if (isset($errore)): ?> <p class="error"><?= $errore ?></p> <?php endif; ?>
    </div>

    <!-- Messaggio errore -->

</div>

</body>
</html>




