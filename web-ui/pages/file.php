<?php
session_start();

// CSRF token
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$filesDir = __DIR__ . '/files';
$logDir   = __DIR__ . '/logs';
@mkdir($filesDir, 0755, true);
@mkdir($logDir, 0755, true);

$yamlFile   = "$filesDir/suricata.yaml";
$rulesFile  = "$filesDir/suricata.rules";

$message = '';

// Copia iniziale file di sistema se non esistono
if(!file_exists($yamlFile)) copy('/etc/suricata/suricata.yaml', $yamlFile);
if(!file_exists($rulesFile)) {
    if(file_exists('/etc/suricata/rules/suricata.rules')) {
        copy('/etc/suricata/rules/suricata.rules', $rulesFile);
    } else {
        file_put_contents($rulesFile, "");
    }
}

// Salvataggio modifiche
if(isset($_POST['save_file'], $_POST['file_name'], $_POST['file_content']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
    $filePath = $filesDir . '/' . basename($_POST['file_name']);
    file_put_contents($filePath, $_POST['file_content']);
    $message = "File {$_POST['file_name']} salvato!";
}

// Ricarica file originale
if(isset($_POST['reload_file'], $_POST['file_name']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
    $fileName = basename($_POST['file_name']);
    if($fileName === 'suricata.yaml') {
        copy('/etc/suricata/suricata.yaml', $yamlFile);
        $message = "suricata.yaml ricaricato dal sistema!";
    } elseif($fileName === 'suricata.rules') {
        if(file_exists('/etc/suricata/rules/suricata.rules')) {
            copy('/etc/suricata/rules/suricata.rules', $rulesFile);
            $message = "suricata.rules ricaricato dal sistema!";
        } else {
            file_put_contents($rulesFile, "");
            $message = "suricata.rules non esiste, creato file vuoto.";
        }
    }
}

// Deploy dei file reali
if(isset($_POST['deploy']) && $_POST['csrf_token'] === $_SESSION['csrf_token']){
    $output = shell_exec('sudo ' . escapeshellcmd(__DIR__ . '/scripts/deploy-suricata.sh') . ' 2>&1');
    $message = "<pre>$output</pre>";
}

// Contenuto per editor
$yamlContent = file_exists($yamlFile) ? file_get_contents($yamlFile) : '';
$rulesContent = file_exists($rulesFile) ? file_get_contents($rulesFile) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Suricata Professional UI</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/mode/yaml/yaml.min.js"></script>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background: #f4f6f8; color: #333; }
h1 { text-align:center; font-size:2rem; margin-bottom:20px; color:#1a1a1a; }
.message { padding:12px 15px; border-radius:5px; background:#dff0d8; color:#3c763d; margin-bottom:15px; font-weight:500; }

/* Bottoni */
button { padding:10px 18px; margin:10px 5px 20px 0; background:#3498db; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:1rem; transition:0.3s; }
button:hover { background:#2980b9; transform:translateY(-1px); }
button:active { transform:translateY(0); }

/* Tabs */
.tabs { display:flex; border-bottom:2px solid #ccc; margin-bottom:10px; border-radius:5px 5px 0 0; overflow:hidden; background:#ecf0f1; }
.tab { padding:12px 25px; cursor:pointer; background:#ecf0f1; color:#34495e; font-weight:600; transition:0.3s; }
.tab.active { background:#fff; border-top:2px solid #3498db; border-left:1px solid #ccc; border-right:1px solid #ccc; color:#3498db; border-bottom:none; }
.tab:hover { background:#dfe6e9; }

/* Contenuto tab */
.tab-content { display:none; padding:15px; background:#fff; border:1px solid #ccc; border-top:none; border-radius:0 0 5px 5px; box-shadow:0 2px 5px rgba(0,0,0,0.05);}
.tab-content.active { display:block; }

/* Editor */
.CodeMirror { height:500px; border:1px solid #bdc3c7; border-radius:5px; font-size:14px; }
/* Gutter corretto per numeri righe */
.CodeMirror-gutters { border-right:1px solid #ccc; background:#f0f0f0; padding-right:5px; min-width:40px; }

/* Responsive */
@media(max-width:768px){ .tabs{flex-direction:column;} .tab{margin-right:0; margin-bottom:2px;} button{width:100%; margin-bottom:10px;} }
</style>
</head>
<body>
<h1>Suricata Professional UI</h1>

<?php if($message) echo "<div class='message'>$message</div>"; ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <button type="submit" name="deploy">Applica file reali su Suricata</button>
</form>

<div class="tabs">
    <div class="tab active" data-target="yaml_tab">suricata.yaml</div>
    <div class="tab" data-target="rules_tab">suricata.rules</div>
</div>

<div id="yaml_tab" class="tab-content active">
    <form method="POST">
        <textarea name="file_content" id="yaml_editor"><?= htmlspecialchars($yamlContent) ?></textarea>
        <input type="hidden" name="file_name" value="suricata.yaml">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" name="save_file">Salva YAML</button>
        <button type="submit" name="reload_file" value="1">Ricarica YAML originale</button>
    </form>
</div>

<div id="rules_tab" class="tab-content">
    <form method="POST">
        <textarea name="file_content" id="rules_editor"><?= htmlspecialchars($rulesContent) ?></textarea>
        <input type="hidden" name="file_name" value="suricata.rules">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" name="save_file">Salva Rules</button>
        <button type="submit" name="reload_file" value="1">Ricarica Rules originale</button>
    </form>
</div>

<script>
// Tab switching
const tabs = document.querySelectorAll('.tab');
const contents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.target).classList.add('active');
    });
});

// CodeMirror Editors
CodeMirror.fromTextArea(document.getElementById('yaml_editor'), {
    mode:'yaml', lineNumbers:true, lineWrapping:true
});
CodeMirror.fromTextArea(document.getElementById('rules_editor'), {
    mode:'text/plain', lineNumbers:true, lineWrapping:true
});
</script>
</body>
</html>
