#!/bin/bash
FILES_DIR="/var/www/html/suricata-ui/files"
RULES_DEST="/etc/suricata/rules/suricata.rules"
YAML_DEST="/etc/suricata/suricata.yaml"
LOG_DIR="/var/www/html/suricata-ui/logs"

mkdir -p $LOG_DIR

# Backup automatico
cp $RULES_DEST "$LOG_DIR/suricata.rules.bak.$(date +%F_%T)" 2>/dev/null
cp $YAML_DEST "$LOG_DIR/suricata.yaml.bak.$(date +%F_%T)" 2>/dev/null

# Copia file dalla UI
cp "$FILES_DIR/suricata.rules" "$RULES_DEST"
cp "$FILES_DIR/suricata.yaml" "$YAML_DEST"

# Riavvia Suricata
systemctl restart suricata

echo "Deploy completato! Backup creati in $LOG_DIR"
