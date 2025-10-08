<?php
session_start();

// CONFIG
// Percorso al file eve.json (modifica se necessario)
$eveFile = '/var/log/suricata/custom.json';
// Quante righe leggere dal file (ultima N)
$readLines = 500;

// Safety: non leggere troppo se l'utente sceglie un valore
if (isset($_GET['lines'])) {
    $requested = intval($_GET['lines']);
    if ($requested > 0 && $requested <= 5000) {
        $readLines = $requested;
    }
}

// Helper: leggi le ultime N righe di un file in modo efficiente
function tailFile($filepath, $lines = 500) {
    $result = [];
    if (!is_readable($filepath)) return $result;

    $handle = fopen($filepath, "r");
    if ($handle === false) return $result;

    $buffer = '';
    $pos = -1;
    $lineCount = 0;
    $chunk = '';

    fseek($handle, 0, SEEK_END);
    $filesize = ftell($handle);
    if ($filesize === 0) { fclose($handle); return $result; }

    while ($lineCount < $lines && -$pos <= $filesize) {
        fseek($handle, $pos, SEEK_END);
        $char = fread($handle, 1);
        if ($char === "\n") {
            if ($buffer !== '') {
                $result[] = strrev($buffer);
                $buffer = '';
                $lineCount++;
            }
        } else {
            $buffer .= $char;
        }
        $pos--;
    }

    if ($buffer !== '' && $lineCount < $lines) {
        $result[] = strrev($buffer);
    }

    fclose($handle);
    return array_reverse($result);
}

// Leggi righe
$rawLines = tailFile($eveFile, $readLines);

// Parse JSON e costruisci array di eventi (solo alert per default)
$events = [];
foreach ($rawLines as $line) {
    $line = trim($line);
    if ($line === '') continue;
    $json = json_decode($line, true);
    if ($json === null) continue; // skip non-json
    // opzionale: mostra solo alert
    if (isset($json['event_type']) && $json['event_type'] !== 'alert') {
        // se vuoi includere tutti, commenta questa riga
        continue;
    }
    $events[] = $json;
}

// invert array so newest first (tailFile returned oldest->newest)
$events = array_reverse($events);

// Utility per sicurezza output
function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8">
<title>Suricata - Ultimi alert (EVE JSON)</title>

<!-- Stili rapidi e puliti -->
<style>
body { font-family: Inter, Roboto, Arial, sans-serif; margin: 18px; background:#f6f8fa; color:#222; }
.container { max-width:1200px; margin:0 auto; }
.header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
h1 { margin:0; font-size:1.4rem; color:#1a1a1a; }
.controls { display:flex; gap:8px; align-items:center; }
button { padding:8px 12px; border-radius:6px; border:none; background:#2b7be4; color:#fff; cursor:pointer; }
button.secondary { background:#6c757d; }
select,input { padding:8px; border-radius:6px; border:1px solid #ccd0d5; }
.message { background:#e9f7ef; color:#18632a; padding:8px 12px; border-radius:6px; }

/* table */
.table-wrap { overflow:auto; background:#fff; border:1px solid #e1e4e8; border-radius:8px; box-shadow:0 1px 3px rgba(15,20,25,0.02); }
table { border-collapse:collapse; width:100%; min-width:1000px; }
thead th { text-align:left; padding:10px 12px; font-size:12px; text-transform:uppercase; color:#4a5568; background:#fbfcfd; position:sticky; top:0; z-index:2; border-bottom:1px solid #e6e8eb; }
tbody td { padding:10px 12px; border-top:1px solid #f1f3f5; font-size:13px; vertical-align:top; }
tr:hover td { background:#fbfbfb; }

/* severity badges */
.badge { display:inline-block; padding:4px 8px; border-radius:999px; font-weight:600; font-size:12px; color:#fff; }
.low { background:#2ecc71; }
.medium { background:#f39c12; }
.high { background:#e74c3c; }

/* small monospace for payloads */
.payload { font-family: Menlo, Monaco, monospace; font-size:12px; color:#333; max-width:320px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* modal */
.modal { position:fixed; inset:0; background:rgba(0,0,0,0.45); display:none; align-items:center; justify-content:center; z-index:9999; }
.modal .card { background:#fff; border-radius:8px; padding:16px; width:90%; max-width:900px; height:80%; overflow:auto; box-shadow:0 8px 30px rgba(2,6,23,0.2); }
.close { float:right; background:#eee; border-radius:6px; padding:4px 8px; cursor:pointer; }

/* responsive */
@media (max-width:900px) {
  table { min-width:800px; }
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div>
      <h1>Suricata - ultimi alert (EVE JSON)</h1>
      <div style="font-size:13px; color:#556; margin-top:4px;">File: <?php echo h($eveFile); ?> — mostrate <?php echo count($events); ?> righe</div>
    </div>

    <div class="controls">
      <form method="get" id="linesForm" style="display:flex; gap:8px; align-items:center;">
        <label for="lines">Ultime righe:</label>
        <input id="lines" name="lines" type="number" min="10" max="5000" value="<?php echo intval($readLines); ?>" style="width:90px">
        <button type="submit">Ricarica</button>
      </form>

      <button class="secondary" onclick="document.location.reload()">Refresh page</button>
      <button onclick="downloadJSON()">Esporta JSON</button>
    </div>
  </div>

  <div class="table-wrap">
    <table id="alertsTable" aria-describedby="tableDesc">
      <thead>
        <tr>
          <th>Timestamp</th>
          <th>Src</th>
          <th>Dst</th>
          <th>Proto</th>
          <th>ICMP</th>
          <th>Action / Severity</th>
          <th>Signature</th>
          <th>Category</th>
          <th>Payload</th>
          <th>Raw</th>
        </tr>
      </thead>
      <tbody>
<?php
// Render rows
foreach ($events as $i => $e) {
    $ts = $e['timestamp'] ?? '';
    $src = ($e['src_ip'] ?? '') . (isset($e['src_port']) && $e['src_port'] ? ':' . $e['src_port'] : '');
    $dst = ($e['dest_ip'] ?? '') . (isset($e['dest_port']) && $e['dest_port'] ? ':' . $e['dest_port'] : '');
    $proto = $e['proto'] ?? ($e['event_type'] ?? '');
    $icmp = isset($e['icmp_type']) ? ($e['icmp_type'] . '/' . ($e['icmp_code'] ?? '')) : '';
    $action = $e['alert']['action'] ?? ($e['alert']['action'] ?? '');
    $severity = $e['alert']['severity'] ?? null;
    $sig = $e['alert']['signature'] ?? ($e['alert']['signature'] ?? '');
    $cat = $e['alert']['category'] ?? '';
    $payload = $e['payload_printable'] ?? ($e['payload'] ?? '');
    $jsonRaw = json_encode($e, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    // severity class
    $sevClass = 'low';
    if ($severity === null) $sevClass = 'low';
    elseif ($severity >= 3) $sevClass = 'high';
    elseif ($severity == 2) $sevClass = 'medium';
    else $sevClass = 'low';

    echo "<tr data-json='".htmlspecialchars($jsonRaw, ENT_QUOTES | ENT_SUBSTITUTE)."'>";
    echo "<td>" . h($ts) . "</td>";
    echo "<td>" . h($src) . "</td>";
    echo "<td>" . h($dst) . "</td>";
    echo "<td>" . h($proto) . "</td>";
    echo "<td>" . h($icmp) . "</td>";
    echo "<td><span class='badge {$sevClass}'>" . h($action) . " / " . ($severity !== null ? h($severity) : 'N/A') . "</span></td>";
    echo "<td>" . h($sig) . "</td>";
    echo "<td>" . h($cat) . "</td>";
    echo "<td><div class='payload' title='" . h($payload) . "'>" . h($payload) . "</div></td>";
    echo "<td><button onclick='showJSON(event)'>View</button></td>";
    echo "</tr>";
}
?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal per raw JSON -->
<div id="modal" class="modal" role="dialog" aria-modal="true">
  <div class="card">
    <button class="close" onclick="closeModal()">Chiudi</button>
    <h3>JSON evento</h3>
    <pre id="jsonContent" style="white-space:pre-wrap; font-family:Menlo,Monaco,monospace; font-size:13px;"></pre>
  </div>
</div>

<script>
// Show JSON modal
function showJSON(e) {
    e = e || window.event;
    var btn = e.currentTarget || e.srcElement;
    var tr = btn.closest('tr');
    var raw = tr.getAttribute('data-json');
    document.getElementById('jsonContent').textContent = raw;
    document.getElementById('modal').style.display = 'flex';
}

// Close modal
function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

// Download current table as JSON file
function downloadJSON() {
    var rows = document.querySelectorAll('#alertsTable tbody tr');
    var out = [];
    rows.forEach(r => {
        var j = r.getAttribute('data-json');
        try {
            out.push(JSON.parse(j));
        } catch (err) {}
    });
    var blob = new Blob([JSON.stringify(out, null, 2)], {type: 'application/json'});
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'suricata_alerts.json';
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}
</script>
</body>
</html>
