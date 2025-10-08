<!DOCTYPE html>
<?php include_once __DIR__ . '/../config/config.php'; ?>

<html lang="en">
<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Logs</title>
</head>

<body id="page-eve">

  <?php include_once ROOT_PATH . 'includes/navbar.php'; ?>

  <div class="main-container">
    <?php include_once ROOT_PATH . 'includes/sidebar.php'; ?>

    <div class="main-content" id="main-content">
      <div class="content">
        <h3>Eve Logs</h3>
        <div id="table-container"></div>
        </div> 
    </div>
  </div>

</body>
  
<?php include_once ROOT_PATH . 'includes/script.php'; ?>

</html>