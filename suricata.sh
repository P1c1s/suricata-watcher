#!/bin/bash
# ==============================================
# Setup HTTPS for Apache2 with Self-Signed SSL
# Domain: suricata.watcher.local
# ==============================================

DOMAIN="suricata.watcher.local"
SSL_DIR="/etc/ssl/suricata"
APACHE_CONF="/etc/apache2/sites-available/${DOMAIN}.conf"

echo "🔧 Creazione cartella certificati..."
mkdir -p "$SSL_DIR"

echo "🔐 Generazione chiave privata e certificato autofirmato..."
openssl req -x509 -nodes -days 365 \
  -newkey rsa:2048 \
  -keyout "${SSL_DIR}/${DOMAIN}.key" \
  -out "${SSL_DIR}/${DOMAIN}.crt" \
  -subj "/C=IT/ST=Italia/L=Milano/O=Watcher/OU=Security/CN=${DOMAIN}"

echo "⚙️ Creazione configurazione Apache..."
bash -c "cat > ${APACHE_CONF}" <<EOF
<VirtualHost *:80>
    ServerName ${DOMAIN}
    Redirect permanent / https://${DOMAIN}/
</VirtualHost>

<VirtualHost *:443>
    ServerName ${DOMAIN}

    DocumentRoot /var/www/html/web-ui

    SSLEngine on
    SSLCertificateFile ${SSL_DIR}/${DOMAIN}.crt
    SSLCertificateKeyFile ${SSL_DIR}/${DOMAIN}.key

    <Directory /var/www/html/web-ui>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/${DOMAIN}_error.log
    CustomLog \${APACHE_LOG_DIR}/${DOMAIN}_access.log combined
</VirtualHost>
EOF

echo "🔧 Abilitazione moduli e configurazione SSL..."
a2enmod ssl
a2enmod rewrite
a2ensite "${DOMAIN}.conf"

echo "🚀 Riavvio Apache2..."
systemctl reload apache2

echo "✅ Configurazione completata!"
echo "Puoi accedere ora a: https://${DOMAIN}"
echo "⚠️ Potrebbe apparire un avviso del browser (certificato autofirmato)."
