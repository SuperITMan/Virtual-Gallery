#!/bin/bash
# Script to launch Docker files for api RestFul + reste

. ~/Virtual-Gallery/install/virtual-gallery-config.sh

GREEN="\\033[1;32m"
NORMAL="\\033[0m"
BOLD="\\033[1m"
RED="\\033[1;31m"
spin="-\|/"

developmentProject=true
logLocation="/var/log"

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

echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "          Installation et exécution de votre projet"
echo -e "------------------------------------------------------------"${NORMAL}

# Download docker images
printf "\rTéléchargement de superitman/virtual-gallery:mysql..."
docker pull superitman/virtual-gallery:mysql > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de superitman/virtual-gallery:mysql... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de superitman/virtual-gallery:mysql... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rTéléchargement de superitman/virtual-gallery:client..."
docker pull superitman/virtual-gallery:client > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de superitman/virtual-gallery:client... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de superitman/virtual-gallery:client... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rTéléchargement de superitman/virtual-gallery:api..."
docker pull superitman/virtual-gallery:api > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de superitman/virtual-gallery:api... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de superitman/virtual-gallery:api... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rTéléchargement de superitman/virtual-gallery:admin..."
docker pull superitman/virtual-gallery:admin > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de superitman/virtual-gallery:admin... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de superitman/virtual-gallery:admin... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rTéléchargement de phpmyadmin/phpmyadmin..."
docker pull phpmyadmin/phpmyadmin > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de phpmyadmin/phpmyadmin... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de phpmyadmin/phpmyadmin... "${GREEN}"fait"${NORMAL}
echo ""

###############################################################################
################################ Docker MySQL #################################
###############################################################################

# TODO Analyze how to get logs from mysql
#if [ ! -d "${logLocation}/${containerNamePrefix}-database" ]; then
#    echo "Création du dossier de logs pour \"Base de données\""
#    mkdir -p ${logLocation}/${containerNamePrefix}-database
#
#fi
printf "\rDémarrage du container \"Base de données\"..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=${containerNamePrefix}-database)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f ${containerNamePrefix}-database > /dev/null
fi

docker run -it -d \
--name ${containerNamePrefix}-database \
-v ${volumeMySQLLocation}:/var/lib/mysql \
-e MYSQL_ROOT_PASSWORD="${passwordMySQL}" \
--restart="always" \
superitman/virtual-gallery:mysql  > /dev/null

printf "\rDémarrage du container \"Base de données\"... "${GREEN}"fait"${NORMAL}
echo ""

# Wait until docker database initialised
# Because dockers API and Admin have a link to the database
printf "\rAttente de l'initialisation complète du container \"Base de données\"..."
until [ "`/usr/bin/docker inspect -f {{.State.Running}} ${containerNamePrefix}-database`" == "true" ]; do
    i=$(( (i+1) %4 ))
    printf "\rAttente de l'initialisation complète du container \"Base de données\"... "${spin:$i:1}
    sleep 0.1;
done;
printf "\rAttente de l'initialisation complète du container \"Base de données\"... "${GREEN}"fait"${NORMAL}
echo ""

###############################################################################
################################# Docker API ##################################
###############################################################################

if [ ! -d "${logLocation}/${containerNamePrefix}-API" ]; then
    printf "Création du dossier de logs pour \"API\""
    echo ""
    mkdir -p ${logLocation}/${containerNamePrefix}-API
fi

printf "\rDémarrage du container \"API\"..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=${containerNamePrefix}-API)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f ${containerNamePrefix}-API > /dev/null
fi

docker run -it -d \
--name ${containerNamePrefix}-API \
-v ${volumeUploadsLocation}:/var/www/public/uploads \
-v ${volumeConfigLocation}:/var/www/config \
-v ${logLocation}/${containerNamePrefix}-API:/var/log/apache2 \
--link ${containerNamePrefix}-database:db \
-e "VIRTUAL_HOST="${APIDomain} \
-e "LETSENCRYPT_TEST="${developmentProject} \
-e "LETSENCRYPT_HOST="${APIDomain} \
-e "LETSENCRYPT_EMAIL="${emailAddress} \
--restart="always" \
superitman/virtual-gallery:api > /dev/null

printf "\rDémarrage du container \"API\"... "${GREEN}"fait"${NORMAL}
echo ""

# Wait until API has generated the certificate for the JWT
# JWT: Json Web Token. Go on https://jwt.io/ for more informations

until [ -f "${volumeConfigLocation}/ssl/jwt.key" ]; do
    sleep 0.1;
done;
# TODO mettre un condition de fin pour quitter la boucle en cas d'échec

###############################################################################
################################ Docker Client ################################
###############################################################################

printf "\rDémarrage du container \"Client\"..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=${containerNamePrefix}-client)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f ${containerNamePrefix}-client > /dev/null
fi

docker run -it -d \
--name ${containerNamePrefix}-client \
-v ${volumeUploadsLocation}:/usr/src/app/dist/uploads:ro \
-e "VIRTUAL_HOST=$websiteDomain" \
-e "LETSENCRYPT_TEST="${developmentProject} \
-e "LETSENCRYPT_HOST="${websiteDomain} \
-e "LETSENCRYPT_EMAIL="${emailAddress} \
--restart="always" \
superitman/virtual-gallery:client > /dev/null

printf "\rDémarrage du container \"Client\"... "${GREEN}"fait"${NORMAL}
echo ""


###############################################################################
################################ Docker Admin #################################
###############################################################################

if [ ! -d "${logLocation}/${containerNamePrefix}-admin" ]; then
    echo "Création du dossier de logs pour \"Administration\""
    mkdir -p ${logLocation}/${containerNamePrefix}-admin
fi

printf "\rDémarrage du container \"Administration\"..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=${containerNamePrefix}-admin)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f ${containerNamePrefix}-admin > /dev/null
fi

docker run -it -d \
--name ${containerNamePrefix}-admin \
-v ${volumeUploadsLocation}:/var/www/public/uploads \
-v ${volumeConfigLocation}:/var/www/config \
-v ${logLocation}/${containerNamePrefix}-admin:/var/log/apache2 \
--link ${containerNamePrefix}-database:db \
-e "VIRTUAL_HOST="${adminDomain} \
-e "LETSENCRYPT_TEST="${developmentProject} \
-e "LETSENCRYPT_HOST="${adminDomain} \
-e "LETSENCRYPT_EMAIL="${emailAddress} \
--restart="always" \
superitman/virtual-gallery:admin > /dev/null

printf "\rDémarrage du container \"Administration\"... "${GREEN}"fait"${NORMAL}
echo ""

###############################################################################
############################# Docker phpMyAdmin ###############################
###############################################################################

printf "\rDémarrage du container \"phpMyAdmin\"..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=${containerNamePrefix}-phpmyadmin)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f ${containerNamePrefix}-phpmyadmin > /dev/null
fi

docker run -it -d \
--name ${containerNamePrefix}-phpmyadmin \
--link ${containerNamePrefix}-database:db \
-e "VIRTUAL_HOST="${phpmyadminDomain} \
-e "LETSENCRYPT_TEST="${developmentProject} \
-e "LETSENCRYPT_HOST="${phpmyadminDomain} \
-e "LETSENCRYPT_EMAIL="${emailAddress} \
--restart="always" \
phpmyadmin/phpmyadmin > /dev/null

printf "\rDémarrage du container \"phpMyAdmin\"... "${GREEN}"fait"${NORMAL}
echo ""


sleep 2
projectInstall=1
