<?php
header('Content-Type: application/json');

/**
 * Classe responsabile di leggere e restituire i dati grezzi dal file JSON
 */
class SuricataReader
{
    private string $token;
    private string $filePath;

    public function __construct(string $token, string $filePath)
    {
        $this->token = $token;
        $this->filePath = $filePath;
    }

    /** Verifica token di autenticazione */
    public function setAuthentication(bool $check = true): void
    {
        if(!$check) return;

            $headers = getallheaders();
            $authHeader = $headers['Authorization'] ?? '';

            if (strpos($authHeader, 'Bearer ') !== 0 || substr($authHeader, 7) !== $this->token) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized - Invalid Token']);
                exit;
            }
    }

    /** Legge il file JSON line-by-line e restituisce i log */
    public function getLogs(): array
    {
        if (!file_exists($this->filePath)) {
            http_response_code(404);
            echo json_encode(['error' => 'File not found']);
            exit;
        }

        $logs = [];
        $handle = fopen($this->filePath, 'r');

        if (!$handle) {
            http_response_code(500);
            echo json_encode(['error' => 'Cannot open log file']);
            exit;
        }

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') continue;

            $json = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $logs[] = $json;
            }
        }

        fclose($handle);
        return array_reverse($logs);
    }

    public function getLogsJson(): void 
    {
        
    }
}

