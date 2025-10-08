<?php
include_once __DIR__ . '/../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Suricata Watcher | Lock screen</title>
</head>

<body>

<div class="login-box">
    <!-- Logo sopra il form -->
    <img class="form-logo" src="<?= BASE_URL ?>assets/img/suricata.svg" alt="Suricata logo">

    <div class="form-container">
        <form method="post" class="form-card">
            <!-- Titolo dentro il form -->
            <p class="form-title">Login</p>

            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <input type="text" name="username" placeholder="Username" required class="form-control">
            <input type="password" name="password" placeholder="Password" required class="form-control">
            
            <button type="submit" class="btn btn-gray">Login</button>

            <?php if (isset($error)): ?> 
                <p class="form-error"><?= htmlspecialchars($error) ?></p> 
            <?php endif; ?>
        </form>

    </div>
</div>
</body>
</html>
