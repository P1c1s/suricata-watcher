<?php

$hostname = gethostname();
$server_ip = gethostbyname($hostname);
$base = BASE_URL;

echo <<<HTML
<div class="navbar">
  <div class="navbar-left">
    <img src="{$base}assets/img/suricata.svg" alt="Suricata Watcher logo">
  </div>      
  <div class="navbar-right">
    <div class="clock" id="clock">--:--:--</div>
    <div class="divider"></div>
    <div class="hostname">
      Server: {$hostname} ({$server_ip})
    </div>
    <div class="divider"></div>
    <div class="notification" data-count="3">
      <i class="fa-solid fa-bell"></i>
    </div>
    <div class="divider"></div>
    <i class="fa-solid fa-gear"></i>
    <div class="divider"></div>
    <form action="{$base}index.php" method="POST">
      <input type="hidden" id="csrf_token" name="csrf_token" value="{$_SESSION['csrf_token']}">
      <button type="submit" name="logout" class="btn btn-red">Logout</button>
    </form>
  </div>

</div>
HTML;
?>
