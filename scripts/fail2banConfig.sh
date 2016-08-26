#!/bin/bash
# Script to configure fail2ban before launch it

. ~/Virtual-Gallery/install/virtual-gallery-config.sh
userHomeDir=$(eval echo "~$different_user")
fail2banConfigDirPath="$userHomeDir/Virtual-Gallery/install/fail2ban"
jailLocalFail2BanPath=${fail2banConfigDirPath}"/jail.local"
filterDFail2BanDirPath=${fail2banConfigDirPath}"/filter.d"
filterAdminAuthPath=${filterDFail2BanDirPath}"/"${containerNamePrefix}"-admin-auth"

logLocation="/var/log"

GREEN="\\033[1;32m"
NORMAL="\\033[0m"
BOLD="\\033[1m"
RED="\\033[1;31m"

clear
if [ -n ${dockerInstall+x} ]; then
    if [ "${dockerInstall}" = "1" ]; then echo -e "Exécution du script d'installation de Docker... "${GREEN}"fait"${NORMAL};
    elif [ "${dockerInstall}" = "-1" ]; then echo -e "Exécution du script d'installation de Docker... "${RED}"a échoué"${NORMAL}; fi
fi

if [ -n ${iptablesConfig+x} ]; then
    if [ "${iptablesConfig}" = "1" ]; then echo -e "Exécution du script de configuration du pare-feu... "${GREEN}"fait"${NORMAL};
    elif [ "${iptablesConfig}" = "-1" ]; then echo -e "Exécution du script de configuration du pare-feu... "${RED}"a échoué !"${NORMAL}; fi
fi

if [ -n ${nginxInstall+x} ]; then
    if [ "${nginxInstall}" = "1" ]; then echo -e "Exécution du script d'installation de nginx-proxy et let's encrypt... "${GREEN}"fait"${NORMAL};
    elif [ "${nginxInstall}" = "-1" ]; then echo -e "Exécution du script d'installation de nginx-proxy et let's encrypt... "${RED}"a échoué"${NORMAL}; fi
fi

if [ -n ${projectConfig+x} ]; then
    if [ "${projectConfig}" = "1" ]; then echo -e "Exécution du script de configuration de votre projet... "${GREEN}"fait"${NORMAL};
    elif [ "${projectConfig}" = "-1" ]; then echo -e "Exécution du script de configuration de votre projet... "${RED}"a échoué !"${NORMAL}; fi
fi

if [ -n ${projectInstall+x} ]; then
    if [ "${projectInstall}" = "1" ]; then echo -e "Exécution du script d'installation de votre projet... "${GREEN}"fait"${NORMAL};
    elif [ "${projectInstall}" = "-1" ]; then echo -e "Exécution du script d'installation de votre projet... "${RED}"a échoué !"${NORMAL}; fi
fi

echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "                 Configuration de Fail2Ban"
echo -e "------------------------------------------------------------"${NORMAL}

#Create jail.local
echo -ne "Création du fichier jail.local nécessaire à fail2ban...\r"

if [ ! -d "${fail2banConfigDirPath}" ];then
    mkdir -p ${fail2banConfigDirPath}
else
    if [ -f "${jailLocalFail2BanPath}" ]; then
        rm ${jailLocalFail2BanPath}
    fi
fi
touch ${jailLocalFail2BanPath}
chmod +x ${jailLocalFail2BanPath}

cat <<-EOF > ${jailLocalFail2BanPath}
[DEFAULT]

# "ignoreip" can be an IP address, a CIDR mask or a DNS host. Fail2ban will not
# ban a host which matches an address in this list. Several addresses can be
# defined using space separator.
ignoreip = 127.0.0.1/8

# "bantime" is the number of seconds that a host is banned.
bantime  = 600

# A host is banned if it has generated "maxretry" during the last "findtime"
# seconds.
findtime  = 600

# "maxretry" is the number of failures before a host get banned.
maxretry = 5

#
# JAILS
#

[ssh]
enabled = true
port    = ssh
filter  = sshd
logpath  = /var/log/host/auth.log
maxretry = 6

[vg-admin-auth]
enabled = true
filter = ${containerNamePrefix}-admin-auth
action = docker-iptables-multiport[name=NoAuthFailures, port="http,https"]
port = http,https
logpath = /var/log/host/nginx-proxy/*access*.log
maxretry = 3

EOF

echo -ne "Création du fichier jail.local nécessaire à fail2ban... "${GREEN}"fait"${NORMAL}"\r"
echo ""

# Create admin auth filter
echo -ne "Création du du filtre admin-auth pour bloquer les tentatives d'intrusion dans l'administration...\r"

if [ ! -d "${filterDFail2BanDirPath}" ];then
    mkdir -p ${filterDFail2BanDirPath}
else
    if [ -f "${filterAdminAuthPath}" ]; then
        rm ${filterAdminAuthPath}
    fi
fi

touch ${filterAdminAuthPath}
chmod +x ${filterAdminAuthPath}

cat <<-EOF > ${filterAdminAuthPath}
# Virtual Gallery brute force auth filter: /etc/fail2ban/filter.d/virtual-gallery-admin-auth.conf
#
# Block IPs trying to auth on Virtual-Gallery
#
# Matches e.g.
# admin.tfe.ags.ovh 10.0.0.1 - - [12/Aug/2016:09:49:00 +0000] "GET /login.php HTTP/1.1" 200 1343
#
[Definition]
failregex = ^${adminDomain} <HOST> .* "POST /login.php HTTP/*.*" 200
ignoreregex =

EOF
echo -ne "Création du du filtre admin-auth pour bloquer les tentatives d'intrusion dans l'administration... "${GREEN}"fait"${NORMAL}"\r"
echo ""

sleep 2
fail2banConfig=1