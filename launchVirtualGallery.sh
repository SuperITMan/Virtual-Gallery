#!/bin/bash
# Script which downloads last version of scripts and permits to launch the different script

GREEN="\\033[1;32m"
RED="\\033[1;31m"
NORMAL="\\033[0;39m"

scriptsLocation="./Virtual-Gallery/scripts"

if [ ! -d ${scriptsLocation} ]; then
    mkdir -p ${scriptsLocation}
fi

# Check if there is an internet connection
echo -ne "Test de votre connexion à internet...\r"
ping -c3 8.8.8.8 >/dev/null
test_ping=$?
echo -ne "Test de votre connexion à internet... "${GREEN}"fait"${NORMAL}"\r"
echo ""

if [ $test_ping -eq 0 ]; then
# Download scripts on Github
echo "Téléchargement des scripts nécessaires au fonctionnement du panel."
echo -ne '0%   [                                                                      >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/dockerInstall.sh -O ${scriptsLocation}/dockerInstall.sh
echo -ne '14%  [==========                                                            >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/iptablesConfig.sh -O ${scriptsLocation}/iptablesConfig.sh
echo -ne '28%  [====================                                                  >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/nginxInstall.sh -O ${scriptsLocation}/nginxInstall.sh
echo -ne '43%  [==============================                                        >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/projectInstall.sh -O ${scriptsLocation}/projectInstall.sh
echo -ne '57%  [========================================                              >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/projectConfig.sh -O ${scriptsLocation}/projectConfig.sh
echo -ne '71%  [==================================================                    >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/fail2banConfig.sh -O ${scriptsLocation}/fail2banConfig.sh
echo -ne '85%  [============================================================          >]\r'
sleep 1
wget -q https://raw.githubusercontent.com/SuperITMan/Virtual-Gallery/master/scripts/fail2banInstall.sh -O ${scriptsLocation}/fail2banInstall.sh
echo -ne '100% [======================================================================>]\r'
sleep 1
echo ""
fi

# Attribute execution permissions on the scripts
if [[ ! -f ${scriptsLocation}/dockerInstall.sh || ! -f ${scriptsLocation}/iptablesConfig.sh \
    || ! -f ${scriptsLocation}/nginxInstall.sh || ! -f ${scriptsLocation}/projectInstall.sh \
    || ! -f ${scriptsLocation}/fail2banConfig.sh || ! -f ${scriptsLocation}/fail2banInstall.sh ]]; then
    echo -e "$RED""Erreur:""$NORMAL"" Il manque un ou plusieurs scripts nécessaires à l'exécution de ce script."
    echo "Veuillez relancer ce script lorsque vous aurez accès à internet pour télécharger l'intégralité des scripts."
else

chmod +x ${scriptsLocation}/dockerInstall.sh
chmod +x ${scriptsLocation}/iptablesConfig.sh
chmod +x ${scriptsLocation}/nginxInstall.sh
chmod +x ${scriptsLocation}/projectInstall.sh
chmod +x ${scriptsLocation}/projectConfig.sh
chmod +x ${scriptsLocation}/fail2banConfig.sh
chmod +x ${scriptsLocation}/fail2banInstall.sh

while :
do
    dockerInstall="0"
    iptablesConfig="0"
    nginxInstall="0"
    projectInstall="0"
    projectConfig="0"
    fail2banConfig="0"
    fail2banInstall="0"
	clear
cat<<EOF
================================================================
                       Galerie Virtuelle
================================================================
Veuillez choisir votre option

    [1] Installation complète du projet
        - docker, nginx-proxy, let's encrypt, pare-feu
          configuration projet, lancement du projet, fail2ban

    [2] Installation Docker

    [3] Configuration pare-feu

    [4] Exécution, redémarrage ou mise à jour de nginx-proxy
        et let's encrypt

    [5] Configuration du projet
        - noms de domaine, emplacement du projet sur le disque,
          adresse email, nom du site, certificat https

    [6] Exécution, redémarrage ou mise à jour du projet
        - lancement des containers api, admin, client
          et base de données

    [7] Installation et configuration de Fail2Ban

    [Q]uitter le script

----------------------------------------------------------------

EOF

	read -n1 -s choice

	case "$choice" in

	# Complete install
	"1")
	    . ${scriptsLocation}/dockerInstall.sh
	    if [[ -n dockerInstallOK && ${dockerInstallOK}=true ]]; then
            . ${scriptsLocation}/iptablesConfig.sh
            . ${scriptsLocation}/nginxInstall.sh
            . ${scriptsLocation}/projectConfig.sh
            . ${scriptsLocation}/projectInstall.sh
            . ${scriptsLocation}/fail2banConfig.sh
            . ${scriptsLocation}/fail2banInstall.sh
        else
            echo -e ${RED}"Erreur:"${NORMAL}" Un problème a été rencontré durant l'installation de Docker."\
                "Veuillez réessayer"
        fi
	;;
	"2")
		. ${scriptsLocation}/dockerInstall.sh
	;;

	"3")
		. ${scriptsLocation}/iptablesInstall.sh
	;;

	"4")
	    . ${scriptsLocation}/nginxInstall.sh
    ;;

    "5")
	    . ${scriptsLocation}/projectConfig.sh
    ;;

    "6")
	    . ${scriptsLocation}/projectInstall.sh
    ;;

    "7")
	    . ${scriptsLocation}/fail2banConfig.sh
        . ${scriptsLocation}/fail2banInstall.sh
    ;;

	"Q")
		exit ;;

	"q")
		exit ;;

	 * )  echo "Choix invalide ! "$choice     ;;

	esac
    sleep 1

done

# End of if [ -f ${scriptsLocation}/dockerInstall.sh ] || ...
fi
