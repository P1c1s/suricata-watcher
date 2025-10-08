<!DOCTYPE html>
<?php include_once __DIR__ . '/../config/config.php'; ?>

<html lang="en">
<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Logs</title>
  <style>
    .suricata-stats {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1rem;
  background: #0a0a0a;
  padding: 10px;
}

.stat-card {
  box-shadow: 0 0 8px #00ff8855;
  transition: transform 0.2s;
}
.stat-card:hover {
  transform: scale(1.02);
}

  </style>
</head>

<body id="page-????">

  <?php include_once ROOT_PATH . 'includes/navbar.php'; ?>

  <div class="main-container">
    <?php include_once ROOT_PATH . 'includes/sidebar.php'; ?>
    <div class="main-content" id="main-content">
      <div class="content">
        <h3>Traffic</h3>
        <div id="charts-container"></div>
        </div> 
    </div>
  </div>

</body>
  
<?php include_once ROOT_PATH . 'includes/script.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</html>