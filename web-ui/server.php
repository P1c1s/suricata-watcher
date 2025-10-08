<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');

// Includi le classi
require_once __DIR__ . '/classes/SuricataReader.php';
require_once __DIR__ . '/classes/SuricataAnalyzer.php';

// Configurazione
$VALID_TOKEN = 'edjedeij83neuenf38unenfw9enpodd';
$LOG_FILE = '/var/log/suricata/eve.json';

// Creazione istanze
$reader = new SuricataReader($VALID_TOKEN, $LOG_FILE);
$reader->setAuthentication(true);
$logs = $reader->getLogs();

$analyzer = new SuricataAnalyzer($logs);

// Gestione parametro "action" (es. ?action=stats o ?action=logs)
$action = $_GET['action'] ?? 'stats';

switch ($action) {
    case 'logs':
        echo json_encode($analyzer->getValidEvents(), JSON_PRETTY_PRINT);
        break;

    case 'stats':
    default:
        echo json_encode($analyzer->getStats($LOG_FILE), JSON_PRETTY_PRINT);
        break;
}
