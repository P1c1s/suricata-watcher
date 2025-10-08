<?php include_once __DIR__ . '/../config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Suricata Watcher | 500</title>
</head>
<body id="page-500">

<div class="error-page">
    <!-- Logo -->
    <img class="error-logo" src="<?= BASE_URL ?>assets/img/suricata.svg" alt="Suricata logo">

    <!-- Codice errore -->
    <div class="error-code">500</div>

    <!-- Messaggio -->
    <div class="error-message">Internal Server Error</div>

    <!-- Meerkat alert -->
    <div id="meerkat-alert-500" class="meerkat-alert"></div>

    <!-- Pulsante ritorno -->
    <a href="index.php" class="btn btn-gray">Back to dashbaord</a>
</div>

</body>

<?php include_once ROOT_PATH . 'includes/script.php'; ?>

</html>




