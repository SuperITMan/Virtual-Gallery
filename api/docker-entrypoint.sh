#!/bin/bash
certName="jwt"

if [ -d "/var/www/config/ssl" ]; then
    mkdir -p /var/www/config/ssl
fi

cd /var/www/config/ssl

if [ -f "certSubject.sh" ]; then
    chmod +x certSubject.sh
    ./certSubject.sh
fi

if [ -z ${subject+x} ]; then
    subject="/C=BE/OU=Virtual-Gallery/CN=example.com/emailAddress=admin@example.com"
fi

openssl req -nodes -x509 -newkey rsa:2048 -days 360 -keyout ${certName}.key -out ${certName}.csr -subj ${subject}

exec "$@"