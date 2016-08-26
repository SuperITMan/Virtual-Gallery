#!/bin/bash
# Script to configure and install Docker on Debian Jessie

# Variables for this script
GREEN="\\033[1;32m"
NORMAL="\\033[0m"
BOLD="\\033[1m"
RED="\\033[1;31m"
spin="-\|/"
dockerSourceListPath="/etc/apt/sources.list.d/docker.list"

clear
echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "          Installation et configuration de Docker"
echo -e "------------------------------------------------------------"${NORMAL}

# Update available packages list
printf "\rMise à jour de la liste des paquets disponibles sur les dépôts..."
apt-get -qq update > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rMise à jour de la liste des paquets disponibles sur les dépôts... "${spin:$i:1}
  sleep .1
done
printf "\rMise à jour de la liste des paquets disponibles sur les dépôts... "${GREEN}"fait"${NORMAL}
echo ""

# Upgrade packages if needed
echo -e ${BOLD}"Installation des mises à jour disponibles..."${NORMAL}
echo -e ${BOLD}"Cette opération peut durer quelques minutes dépendant du nombre de mises à jour à installer."${NORMAL}
sleep 2
apt-get -qq upgrade -y
echo -e "Installation des mises à jour disponibles... "${GREEN}"fait"${NORMAL}
echo ""

# ============================================================================================= #
# Install https transport and ca-certs

printf "\rInstallation des logiciels nécessaires à l'installation de Docker..."

# Install necessary softs to get Docker
apt-get -q install apt-transport-https ca-certificates -y > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rInstallation des logiciels nécessaires à l'installation de Docker... "${spin:$i:1}
  sleep .1
done
printf "\rInstallation des logiciels nécessaires à l'installation de Docker... "${GREEN}"fait"${NORMAL}
echo ""

echo "Acquisition des clés nécessaires à l'installation de Docker..."
# Register key server of docker repo
apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 \
    --recv-keys 58118E89F3A912897C070ADBF76221572C52609D > /dev/null

echo -e "Acquisition des clés nécessaires à l'installation de Docker... "${GREEN}"fait"${NORMAL}

echo "Détection du système (Debian ou Ubuntu uniquement)..."
OS=$(lsb_release -si)
VERSION=$(lsb_release -sr)

printf "\rAjout des sources nécessaires à l'installation de Docker + Nettoyage des sources..."
# Remove if exists then create Docker source.list file
if [ -f "${dockerSourceListPath}" ]; then
    rm ${dockerSourceListPath}
fi

touch ${dockerSourceListPath}
chmod +x ${dockerSourceListPath}
if [ "${OS}" == "Debian" ]; then
    if [ "${VERSION}" == "7.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Debian Wheezy
deb https://apt.dockerproject.org/repo debian-wheezy main
EOF
    elif [ "${VERSION}" == "8.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Debian Jessie
deb https://apt.dockerproject.org/repo debian-jessie main
EOF
    elif [ "${VERSION}" == "9.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Debian Stretch/Sid
deb https://apt.dockerproject.org/repo debian-stretch main
EOF
    fi;
elif [ "${OS}" == "Ubuntu" ]; then
    if [ "${VERSION}" == "12.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Ubuntu Precise
deb https://apt.dockerproject.org/repo ubuntu-precise main
EOF
    elif [ "${VERSION}" == "14.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Ubuntu Trusty
deb https://apt.dockerproject.org/repo ubuntu-trusty main
EOF
    elif [ "${VERSION}" == "15.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Ubuntu Wily
deb https://apt.dockerproject.org/repo ubuntu-Wily main
EOF
    elif [ "${VERSION}" == "16.*" ]; then
cat <<-EOF > ${dockerSourceListPath}
# Repo of docker for Ubuntu Xenial
deb https://apt.dockerproject.org/repo ubuntu-xenial main
EOF
    fi;
else
    echo "Erreur ! Votre système n'est pas Debian ou Ubuntu. Impossible de continuer l'installation..."
fi;


# Update available packages list
apt-get -q update > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rAjout des sources nécessaires à l'installation de Docker + Nettoyage des sources... "${spin:$i:1}
  sleep .1
done

# Validate docker-engine installation file
apt-cache policy docker-engine > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rAjout des sources nécessaires à l'installation de Docker + Nettoyage des sources... "${spin:$i:1}
  sleep .1
done

printf "\rAjout des sources nécessaires à l'installation de Docker + Nettoyage des sources... "${GREEN}"fait"${NORMAL}
echo ""

# ============================================================================================= #

# Install Docker-engine
printf "\rInstallation de Docker..."
apt-get -q install docker-engine -y > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rInstallation de Docker... "${spin:$i:1}
  sleep .1
done
printf "\rInstallation de Docker... "${GREEN}"fait"${NORMAL}
echo ""


# Start and validate installation of Docker
printf "\rDémarrage de Docker et vérification de son fonctionnement..."
service docker start > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rDémarrage de Docker et vérification de son fonctionnement... "${spin:$i:1}
  sleep .1
done

# Run test machine
docker run hello-world &> testDocker &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rDémarrage de Docker et vérification de son fonctionnement... "${spin:$i:1}
  sleep .1
done

grep1=$(grep "Hello from Docker!" testDocker)
grep2=$(grep "This message shows that your installation appears to be working correctly." testDocker)
if  [[ ${#grep1} -gt 1 && ${#grep2} -gt 1 ]]; then
    dockerInstallOK=true
else
    dockerInstallOK=false
fi

unset grep1
unset grep2
rm testDocker

# Delete test machine
docker rm -f $(docker ps -a -q) > /dev/null &
pid=$! # Process Id of the previous running command
i=0
while kill -0 $pid 2>/dev/null
do
  i=$(( (i+1) %4 ))
  printf "\rDémarrage de Docker et vérification de son fonctionnement... "${spin:$i:1}
  sleep .1
done

printf "\rDémarrage de Docker et vérification de son fonctionnement... "${GREEN}"fait"${NORMAL}
echo ""

sleep 2
dockerInstall="1"
