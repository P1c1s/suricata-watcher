#!/bin/bash
# Script che applica la config modificata da web UI a Suricata

CONFIG_COPY="/var/www/html/suricata-ui/config/suricata.yaml"
CONFIG_REAL="/etc/suricata/suricata.yaml"
BACKUP="/etc/suricata/suricata.yaml.bak.$(date +%F_%T)"

# Backup dell'originale
cp $CONFIG_REAL $BACKUP

# Copia la config modificata
cp $CONFIG_COPY $CONFIG_REAL

# Riavvia Suricata
systemctl restart suricata

echo "Configurazione applicata e Suricata riavviato. Backup: $BACKUP"
