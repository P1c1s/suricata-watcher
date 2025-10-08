<?php

/**
 * Classe responsabile dell’analisi, filtraggio e calcolo delle statistiche
 */
class SuricataAnalyzer
{
    private array $logs;

    public function __construct(array $logs)
    {
        $this->logs = $logs;
    }

    /** Filtra log per tipo di evento (es. alert, http, tls) */
    public function filterByEventType(string $type): array
    {
        return array_values(array_filter($this->logs, fn($log) =>
            ($log['event_type'] ?? '') === $type
        ));
    }

    /** Restituisce solo eventi validi (no "stats" o linee vuote) */
    public function getValidEvents(): array
    {
        return array_values(array_filter($this->logs, fn($log) =>
            isset($log['event_type']) && $log['event_type'] !== 'stats'
        ));
    }

    /** Calcola statistiche globali sui log */
    public function getStats(string $filePath): array
    {
        $validLogs = $this->getValidEvents();

        $totalLines = count($this->logs);
        $validEvents = count($validLogs);

        $eventTypes = [];
        $sources = [];

        foreach ($validLogs as $log) {
            $type = $log['event_type'] ?? 'unknown';
            $eventTypes[$type] = ($eventTypes[$type] ?? 0) + 1;

            if (isset($log['src_ip'])) {
                $sources[$log['src_ip']] = ($sources[$log['src_ip']] ?? 0) + 1;
            }
        }

        arsort($eventTypes);
        arsort($sources);

        $fileSize = file_exists($filePath) ? round(filesize($filePath) / 1024, 2) : 0;
        $lastModified = file_exists($filePath) ? date('Y-m-d H:i:s', filemtime($filePath)) : null;

        return [
            'file' => basename($filePath),
            'file_size_kb' => $fileSize,
            'last_modified' => $lastModified,
            'total_lines' => $totalLines,
            'valid_events' => $validEvents,
            'event_types' => $eventTypes,
            'top_sources' => array_slice($sources, 0, 5, true)
        ];
    }
}

