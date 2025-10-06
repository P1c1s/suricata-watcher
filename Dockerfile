FROM theb0ys/base:latest

# Install Suricata, Apache and PHP dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        suricata \
        apache2 \
        php \
        libapache2-mod-php && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*