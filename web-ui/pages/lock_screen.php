<?php
include_once __DIR__ . '/../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Suricata Watcher | Login</title>
</head>

<body>

<div class="login-box">
    <img class="form-logo" src="<?= BASE_URL ?>assets/img/suricata.svg" alt="Suricata logo">

    <div class="form-container">
        <form method="post" class="form-card">
            <p class="form-title">Lock screen</p>

            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <input type="password" name="pin" placeholder="pin" required class="form-control">
            
            <button type="submit" class="btn btn-gray">Unlock</button>
            
        <?php if (isset($error)): ?>
            <p class="form-error"><?= $error ?></p>
        <?php endif; ?>

        </form>
    </div>

</div>

</body>
</html>

