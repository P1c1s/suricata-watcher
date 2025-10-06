<?php include_once 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'head.php'; ?>
  <title>Suricata Watcher | Dashboard</title>
</head>

<body id="page-index">

  <!-- Top Navbar -->
  <nav class="top-navbar">
    <div class="row">
      <div class="column">
        <img class="top-navbar-logo" src="suricata.svg" alt="Suricata logo">
      </div>
      <div class="column">
        <div class="top-navbar-title">Suricata Watcher</div>
      </div>
    </div>
    <div class="row">
      <div class="col"><a href="file.php" style="color: white">FILE</a></div>
      <div class="col"><a href="traffic.php" style="color: white; margin-left: 10px">T</a></div>
    </div>
    <div class="nav-links">
      <button id="sniffer-button" value="true" class="btn btn-green">Live update ON</button>
      <form action="index.php" method="POST" style="display:inline;">
        <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" name="logout" class="btn btn-red">Logout</button>
      </form>
    </div>
  </nav>

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

</html>
