#!/bin/bash
# Script for fail2ban install and iptables configuration

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
spin="-\|/"

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

if [ -n ${fail2banConfig+x} ]; then
    if [ "${fail2banConfig}" = "1" ]; then echo -e "Exécution du script de configuration de Fail2Ban... "${GREEN}"fait"${NORMAL};
    elif [ "${fail2banConfig}" = "-1" ]; then echo -e "Exécution du script de configuration de Fail2Ban... "${RED}"a échoué !"${NORMAL}; fi
fi

echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "           Installation et exécution de Fail2Ban"
echo -e "------------------------------------------------------------"${NORMAL}

# Download docker image
printf "\rTéléchargement de superitman/fail2ban..."
docker pull superitman/fail2ban:latest > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de superitman/fail2ban... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de superitman/fail2ban... "${GREEN}"fait"${NORMAL}
echo ""

# Launch Fail2Ban docker
printf "\rDémarrage du container \"Fail2Ban\"..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=fail2ban)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f fail2ban > /dev/null
fi

docker run -it -d \
       --name fail2ban \
       --net host \
       --privileged \
       -v ${filterAdminAuthPath}:/etc/fail2ban/filter.d/${containerNamePrefix}-admin-auth.conf \
       -v ${jailLocalFail2BanPath}:/etc/fail2ban/jail.local \
       -v /var/log:/var/log/host \
       superitman/fail2ban:latest > /dev/null

printf "\rDémarrage du container \"Fail2Ban\"... "${GREEN}"fait"${NORMAL}
echo ""

sleep 2
fail2banInstall=1
