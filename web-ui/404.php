<?php
include_once 'config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'head.php'; ?>
  <title>Suricata Watcher | 404</title>
</head>

<body id="page-404">

<div class="error-page">
    <!-- Logo -->
    <img class="form-logo" src="suricata.svg" alt="Suricata logo">

    <!-- Codice errore -->
    <div class="error-code">404</div>

    <!-- Messaggio -->
    <div class="error-message">Page not found</div>

    <!-- Meerkat alert -->
    <div id="meerkat-alert" class="meerkat-alert"></div>

    <!-- Pulsante ritorno -->
    <a href="index.php" class="btn btn-gray">Back to dashbaord</a>
</div>


</body>
</html>




