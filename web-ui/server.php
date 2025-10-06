<?php
header('Content-Type: application/json');

$VALID_TOKEN = 'edjedeij83neuenf38unenfw9enpodd'; // Customize token
// Retrieve Token from Header
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if (strpos($authHeader, 'Bearer ') !== 0 || substr($authHeader, 7) !== $VALID_TOKEN) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized - Invalid Token']);
    exit;
}

// -----------------------------
// Main Logic: Read Suricata Logs
// -----------------------------
$file = '/var/log/suricata/custom.json';

if (file_exists($file)) {
    $logs = [];
    $handle = fopen($file, 'r');

    while (($line = fgets($handle)) !== false) {
        if (trim($line) !== '') {
            $json = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $logs[] = $json;
            } else {
                error_log("Errore di parsing JSON: " . json_last_error_msg());
            }
        }
    }

    fclose($handle);

    $logs = array_reverse($logs);

    echo json_encode($logs);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
}
?>
