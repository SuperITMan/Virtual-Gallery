#!/bin/bash
# Script to instantiate jwilder/nginx-proxy + jrcs/letsencrypt-nginx-proxy-companion

GREEN="\\033[1;32m"
NORMAL="\\033[0m"
BOLD="\\033[1m"
spin="-\|/"

userHomeDir=$(eval echo "~$different_user")
installDirPath=${userHomeDir}"/Virtual-Gallery/install"
nginxProxyConfUpload=${installDirPath}"/nginx-proxy-conf-upload.conf"

clear
if [ -n ${dockerInstall+x} ]; then
    if [ "${dockerInstall}" = "1" ]; then echo -e "Exécution du script d'installation de Docker... "${GREEN}"fait"${NORMAL};
    elif [ "${dockerInstall}" = "-1" ]; then echo -e "Exécution du script d'installation de Docker... "${RED}"a échoué"${NORMAL}; fi
fi

if [ -n ${iptablesConfig+x} ]; then
    if [ "${iptablesConfig}" = "1" ]; then echo -e "Exécution du script de configuration du pare-feu... "${GREEN}"fait"${NORMAL};
    elif [ "${iptablesConfig}" = "-1" ]; then echo -e "Exécution du script de configuration du pare-feu... "${RED}"a échoué !"${NORMAL}; fi
fi

echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "        Configuration et installation de nginx-proxy"
echo "           et de letsencrypt-nginx-proxy-companion"
echo -e "------------------------------------------------------------"${NORMAL}
echo ""

# Download docker images
printf "\rTéléchargement de jwilder/nginx-proxy..."
docker pull jwilder/nginx-proxy > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de jwilder/nginx-proxy... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de jwilder/nginx-proxy... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rTéléchargement de jrcs/letsencrypt-nginx-proxy-companion..."
docker pull jrcs/letsencrypt-nginx-proxy-companion > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rTéléchargement de jrcs/letsencrypt-nginx-proxy-companion... "${spin:$i:1}
  sleep .1
done
printf "\rTéléchargement de jrcs/letsencrypt-nginx-proxy-companion... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rCréation du fichier de configuration pour nginx-proxy..."

if [ ! -d "${installDirPath}" ];then
    mkdir -p ${installDirPath}
else
    if [ -f "${nginxProxyConfUpload}" ]; then
        rm ${nginxProxyConfUpload}
    fi
fi
touch ${nginxProxyConfUpload}

cat <<-EOF > ${nginxProxyConfUpload}
server_tokens off;
client_max_body_size 100m;
EOF

printf "\rCréation du fichier de configuration pour nginx-proxy... "${GREEN}"fait"${NORMAL}
echo ""

printf "\rDémarrage de nginx-proxy..."

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=nginx-proxy)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f nginx-proxy > /dev/null
fi

docker run -it -d \
       --name nginx-proxy \
       -p 80:80 -p 443:443 \
       -v /etc/nginx/vhost.d \
       -v /var/run/docker.sock:/tmp/docker.sock:ro \
       -v /etc/nginx/certs:/etc/nginx/certs:ro \
       -v /usr/share/nginx/html \
       -v /var/log/nginx-proxy:/var/log/nginx \
       -v ${nginxProxyConfUpload}:/etc/nginx/conf.d/upload_config.conf \
       --restart="always" \
       jwilder/nginx-proxy > /dev/null

printf "\rDémarrage de nginx-proxy... "${GREEN}"fait"${NORMAL}
echo ""

# Wait until docker nginx-proxy initialised
# Because docker letsencrypt-nginx-proxy-companion gets volume from nginx-proxy
printf "\rAttente de l'initialisation complète du container \"nginx-proxy\"..."
until [ "`/usr/bin/docker inspect -f {{.State.Running}} nginx-proxy`" == "true" ]; do
    i=$(( (i+1) %4 ))
    printf "\rAttente de l'initialisation complète du container \"nginx-proxy\"... "${spin:$i:1}
    sleep 0.1;
done;
printf "\rAttente de l'initialisation complète du container \"nginx-proxy\"... "${GREEN}"fait"${NORMAL}
echo ""


printf "\rDémarrage de letsencrypt-nginx-proxy-companion"

isRunningDocker=$(/usr/bin/docker ps -q -a -f name=nginx-proxy-lets-encrypt)
if [ ${#isRunningDocker} -gt 0 ]; then
    docker rm -f nginx-proxy-lets-encrypt
fi

docker run -it -d \
       --name nginx-proxy-lets-encrypt \
       -v /var/run/docker.sock:/var/run/docker.sock:ro \
       -v /etc/nginx/certs:/etc/nginx/certs:rw \
       --volumes-from nginx-proxy \
       --restart="always" \
       jrcs/letsencrypt-nginx-proxy-companion > /dev/null

printf "\rDémarrage de letsencrypt-nginx-proxy-companion... "${GREEN}"fait"${NORMAL}
echo ""

sleep 2
nginxInstall="1"
