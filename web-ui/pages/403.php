<?php include_once __DIR__ . '/../config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Suricata Watcher | 403</title>
</head>

<body id="page-403">

<div class="error-page">
    <!-- Logo -->
    <img class="error-logo" src="<?= BASE_URL ?>assets/img/suricata.svg" alt="Suricata logo">

    <!-- Codice errore -->
    <div class="error-code">403</div>

    <!-- Messaggio -->
    <div class="error-message">Resource forbidden</div>

    <!-- Meerkat alert -->
    <div id="meerkat-alert-403" class="meerkat-alert"></div>

    <!-- Pulsante ritorno -->
    <a href="<?= BASE_URL ?>index.php" class="btn btn-gray">Back to dashbaord</a>
</div>

</body>
<script src="../assets/js/script.js"></script>

</html>




