#!/bin/bash
# Script to set default rules for iptables

GREEN="\\033[1;32m"
NORMAL="\\033[0m"
BOLD="\\033[1m"
RED="\\033[1;31m"

clear
if [ -n ${dockerInstall+x} ]; then
    if [ "${dockerInstall}" = "1" ]; then echo -e "Exécution du script d'installation de Docker... "${GREEN}"fait"${NORMAL};
    elif [ "${dockerInstall}" = "-1" ]; then echo -e "Exécution du script d'installation de Docker... "${RED}"a échoué"${NORMAL}; fi
fi

echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "               Configuration de votre pare-feu"
echo -e "------------------------------------------------------------"${NORMAL}
echo -ne "Backup de votre configuration actuelle...\r"
mkdir -p /etc/iptables
iptables-save > /etc/iptables/rules.v4.bak
ip6tables-save > /etc/iptables/rules.v6.bak
echo -ne "Backup de votre configuration actuelle... "${GREEN}"fait"${NORMAL}"\r"
echo ""

echo -ne "Application des nouvelles règles à votre pare-feu...\r"
iptables -A INPUT -p tcp --dport ssh -j ACCEPT
iptables -A INPUT -m conntrack --ctstate ESTABLISHED -j ACCEPT
iptables -I INPUT -i lo -j ACCEPT
iptables -A INPUT -p icmp -j ACCEPT
iptables -A INPUT -p tcp --dport http -j ACCEPT
iptables -A INPUT -p tcp --dport https -j ACCEPT
iptables -P INPUT DROP

# IPv6 is completely blocked in this first version for more security
ip6tables -P INPUT DROP
ip6tables -P OUTPUT DROP
ip6tables -P FORWARD DROP

echo -ne "Application des nouvelles règles à votre pare-feu... "${GREEN}"fait"${NORMAL}"\r"
echo""

echo "Si vous lisez ce message, veuillez appuyer sur la touche Enter pour valider "
echo "la nouvelle configuration de votre pare-feu."
echo "Si aucune action n'est réalisée endéans les 2 minutes,"
read -t 120 -p "la configuration d'origine sera restaurée..." -n1 isFirewallConfigOK
if [ $? -ne 0 ]; then
    echo ""
    iptables-restore -c < /etc/iptables/rules.v4.bak
    ip6tables-restore -c < /etc/iptables/rules.v6.bak
    echo "Votre ancienne configuration a été restaurée."
else
    iptables-save > /etc/iptables/rules.v4
    ip6tables-save > /etc/iptables/rules.v6
    echo "La nouvelle configuration de votre pare-feu a été sauvegardée."
fi

sleep 2
iptablesConfig="1"
