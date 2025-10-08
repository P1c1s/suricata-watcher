<?php

$base = BASE_URL;

echo <<<HTML
<div class="sidebar" id="sidebar">
    <button class="toggle-btn" id="toggle-btn">
        <i class="fa-solid fa-chevron-right" id="toggle-icon"></i>
    </button>
    <ul>
        <li><a href="{$base}index.php"><i class="fa-solid fa-gauge-high"></i> <span>Home</span></a></li>
        <li><a href="{$base}pages/eve.php"><i class="fa-solid fa-box-archive"></i> <span>Logs</span></a></li>
        <li><a href="{$base}pages/traffic.php"><i class="fas fa-network-wired"></i> <span>Traffic</span></a></li>
        <li><a href="{$base}pages/file.php"><i class="fas fa-file-alt"></i> <span>Files</span></a></li>
        <li><a href="#"><i class="fa-solid fa-terminal"></i> <span>Console</span></a></li>
    </ul>
</div>
HTML;
?>
