<?php
session_start();

// Token CSRF
if(!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Configurazione sicura
$configFile = __DIR__ . '/config/suricata.yaml';
$message = '';

// Copia iniziale se non esiste
if(!file_exists($configFile)) {
    copy('/etc/suricata/suricata.yaml', $configFile);
}

// Salvataggio modifica
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['config'], $_POST['csrf_token'])) {
    if($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token non valido!");
    }
    $newConfig = $_POST['config'];
    file_put_contents($configFile, $newConfig);
    $message = "Configurazione salvata! Ricorda di applicarla con il bottone 'Applica'.";
}

// Applicare la config reale
if(isset($_POST['apply']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $output = shell_exec('sudo deploy-suricata.sh 2>&1');
    $message = "Deploy completato:<br><pre>$output</pre>";
}

// Carica config
$config = file_get_contents($configFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Suricata Config Manager</title>
<!-- Codemirror per evidenziazione YAML -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/yaml/yaml.min.js"></script>
<style>
body { font-family: Arial; margin: 20px; }
textarea { width: 100%; height: 500px; }
button { margin-top: 10px; padding: 8px 16px; }
.message { margin: 10px 0; color: green; }
</style>
</head>
<body>
<h1>Suricata YAML Editor</h1>
<?php if($message) echo "<div class='message'>$message</div>"; ?>

<form method="POST">
    <textarea id="config" name="config"><?= htmlspecialchars($config) ?></textarea>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <br>
    <button type="submit">Salva Configurazione</button>
    <button type="submit" name="apply">Applica Config</button>
</form>

<script>
var editor = CodeMirror.fromTextArea(document.getElementById('config'), {
    mode: 'yaml',
    lineNumbers: true,
    matchBrackets: true,
    lineWrapping: true
});
</script>
</body>
</html>
