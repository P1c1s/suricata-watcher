FROM theb0ys/base:latest

# Install Suricata, Apache and PHP dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        suricata \
        apache2 \
        php \
        libapache2-mod-php \
        git && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set Apache document root and clone Suricata Watcher
WORKDIR /var/www/html
RUN rm -rf /var/www/html/* && \
    git clone https://github.com/P1c1s/suricata-watcher.git . 

# Set permissions (opzionale, se Apache usa www-data)
RUN chown -R www-data:www-data /var/www/html

# Expose HTTPs port
EXPOSE 443

# Enable Apache mods (if needed) and start Apache in foreground
RUN a2enmod rewrite
CMD ["apache2ctl", "-D", "FOREGROUND"]
