<?php

$base = BASE_URL;

echo <<<HTML
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="Suricata Watcher" />
<meta name="author" content="Lorenzo Ricciardi" />
<meta name="keywords" content="Suricata, IDS, Dashboard" />
<meta name="robots" content="index, follow" />
<link rel="shortcut icon" href="{$base}assets/img/suricata.svg" type="image/x-icon" />
<link rel="stylesheet" href="{$base}assets/css/main.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
HTML;
?>
