<?php include_once __DIR__ . '/config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once ROOT_PATH . 'includes/head.php'; ?>
  <title>Suricata Watcher | Dashboard</title>
</head>
<body id="page-index">

<!-- Pulsante toggle per aprire/chiudere la sidebar -->
<button id="sidebar-toggle">
  ☰
</button>

<div class="sidebar" id="sidebar">
  <img src="assets/img/suricata.svg" alt="Logo" class="sidebar-logo">

  <ul>
    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li><a href="file.php"><i class="fas fa-file"></i> File</a></li>
    <li><a href="traffic.php"><i class="fas fa-network-wired"></i> Traffic</a></li>
    <li><a href="#"><i class="fas fa-cogs"></i> Settings</a></li>
  </ul>

  <div class="sidebar-footer">
    Suricata Watcher &copy; 2025
  </div>
</div>


  <!-- Navbar -->
  <?php include ROOT_PATH . 'includes/navbar.php'; ?>

  <main style="padding:20px;">
    <h1>Dashboard</h1>

    <section class="table-section">
      <table id="data-table" class="data-table">
        <thead>
          <tr>
            <?php
            $columns = [
              'Data' => 'timestamp',
              'Source IP' => 'src-ip',
              'Destination IP' => 'dest-ip',
              'Protocol' => 'proto',
              'ICMP Type' => 'icmp-type',
              'Action' => 'action',
              'Severity' => 'severity',
              'Signature' => 'signature',
              'Category' => 'category',
              'Interface' => 'in_iface'
            ];

            foreach ($columns as $label => $id) {
              $type = $id === 'severity' ? 'number' : 'text';
              $min = $id === 'severity' ? 'min="0"' : '';
              echo "<th>$label<br /><input type=\"$type\" id=\"filter-$id\" placeholder=\"Filtra $label\" $min /></th>";
            }
            ?>
            <th>Raw</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

      <div id="pagination-controls" class="pagination-controls"></div>
    </section>
  </main>
</body>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebar-toggle');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
  });

  // Chiudi sidebar cliccando fuori (solo mobile)
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
      if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    }
  });
});

</script>
</html>
