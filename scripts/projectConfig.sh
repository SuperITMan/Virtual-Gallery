#!/bin/bash
# Script to make the config of the project
# contains : site_url, api_url, admin_url, ...

BOLD="\\033[1m"
GREEN="\\033[1;32m"
NORMAL="\\033[0m"
RED="\\033[1;31m"

userHomeDir=$(eval echo "~$different_user")
installDirPath="$userHomeDir/Virtual-Gallery/install"
installConfigFilePath="$installDirPath/virtual-gallery-config.sh"
manifestClientPath="$installDirPath/client-manifest.json"
defaultContainerNamePrefix="virtual-gallery"
defaultVolumeLocation="$userHomeDir/Virtual-Gallery"
defaultWebsiteName="Virtual Gallery"
defaultCountry="BE"
defaultCity="Brussels"
openSSLFileConfig="openSSLFileConfig.sh"

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

echo ""
echo -e ${BOLD}"------------------------------------------------------------"
echo "               Configuration de votre projet"
echo -e "------------------------------------------------------------"${NORMAL}

# Recover file configuration
if [ -d ${installDirPath} ]; then
    if [ -f ${installConfigFilePath} ]; then
        . ${installConfigFilePath}
        echo -e "Récupération de votre ancien fichier de configuration..."${GREEN}"fait"${NORMAL}
    fi
fi


# Complete configuration
echo "Configuration de votre infrastructure Docker pour votre site basé sur le projet Virtual-Gallery"
while [ "${isGoodConfig}" != "o" ] && [ "${isGoodConfig}" != "O" ]; do

    # Website domain
    websiteDomainOK=false

	while [ ${websiteDomainOK} = false ]; do
		tmpWebsiteDomain=${websiteDomain}
		echo -n "Veuillez entrer le domaine lié à votre site ["${tmpWebsiteDomain}"] : "
		read websiteDomain
		if [ -z "${websiteDomain}" ]; then
			websiteDomain=${tmpWebsiteDomain}
		fi
		if [ -z "${websiteDomain}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			if [[ "${websiteDomain}" =~ [a-zA-Z0-9]{1,}+\.[a-zA-Z]{2,} ]]; then
			    websiteDomainOK=true
            else
                echo -e ${RED}"Erreur: "${NORMAL}"Le nom de domaine entré est incorrect. Veuillez recommencer."
            fi
		fi
	done

	# Administration domain
	defaultAdminDomain="admin."${websiteDomain}
	adminDomainOK=false

	while [ ${adminDomainOK} = false ]; do
		if [ -z "${adminDomain}" ]; then
			tmpAdminDomain=${defaultAdminDomain}
		else
			tmpAdminDomain=${adminDomain}
		fi

		echo -n "Veuillez entrer le domaine lié à l'administration de votre site ["${tmpAdminDomain}"] : "
		read adminDomain
		if [ -z "${adminDomain}" ]; then
			adminDomain=${tmpAdminDomain}
		fi
		if [ -z "${adminDomain}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			adminDomainOK=true
		fi
	done

	# RestFul API domain
	defaultAPIDomain="api."${websiteDomain}
	APIDomainOK=false

	while [ ${APIDomainOK} = false ]; do
		if [ -z "${APIDomain}" ]; then
			tmpAPIDomain=${defaultAPIDomain}
		else
			tmpAPIDomain=${APIDomain}
		fi

		echo -n "Veuillez entrer le domaine lié à l'API de votre site ["${tmpAPIDomain}"] : "
		read APIDomain
		if [ -z "${APIDomain}" ]; then
			APIDomain=${tmpAPIDomain}
		fi
		if [ -z "${APIDomain}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			APIDomainOK=true
		fi
	done

	# phpMyAdmin domain
	defaultphpmyadminDomain="phpmyadmin."${websiteDomain}
	phpmyadminDomainOK=false

	while [ ${phpmyadminDomainOK} = false ]; do
		if [ -z "${phpmyadminDomain}" ]; then
			tmpphpmyadminDomain=${defaultphpmyadminDomain}
		else
			tmpphpmyadminDomain=${phpmyadminDomain}
		fi

		echo -n "Veuillez entrer le domaine lié à l'API de votre site ["${tmpphpmyadminDomain}"] : "
		read phpmyadminDomain
		if [ -z "${phpmyadminDomain}" ]; then
			phpmyadminDomain=${tmpphpmyadminDomain}
		fi
		if [ -z "${phpmyadminDomain}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			phpmyadminDomainOK=true
		fi
	done

    # Mail address to generate certificate HTTPS with Let's Encrypt
    # For more informations on Let's Encrypt, go on https://letsencrypt.org/
    emailAddressOK=false

	while [ ${emailAddressOK} = false ]; do
		tmpEmailAddress=${emailAddress}
		echo -n "Veuillez entrer l'adresse email à utiliser pour générer les certificats HTTPS ["${tmpEmailAddress}"] : "
		read emailAddress
		if [ -z "${emailAddress}" ]; then
			emailAddress=${tmpEmailAddress}
		fi
		if [ -z "${emailAddress}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
		    if [[ "${emailAddress}" =~ [a-zA-Z0-9._+-]{1,}+@[A-Za-z0-9]{2,}+\.[a-zA-Z]{2,} ]]; then
		        emailAddressOK=true
            else
                echo -e ${RED}"Erreur: "${NORMAL}"L'adresse email entrée est incorrecte. Veuillez recommrncer."
            fi
		fi
	done

	# Location of uploads folder, linked to API, Administration and client website itself
	defaultVolumeUploadsLocation=${defaultVolumeLocation}"/uploads"
	volumeUploadsLocationOK=false

	while [ ${volumeUploadsLocationOK} = false ]; do
		if [ -z "${volumeUploadsLocation}" ]; then
			tmpVolumeUploadsLocation=${defaultVolumeUploadsLocation}
		else
			tmpVolumeUploadsLocation=${volumeUploadsLocation}
		fi

		echo -n "Veuillez entrer le path complet du volume accocié au dossier uploads de votre site ["${tmpVolumeUploadsLocation}"] : "
		read volumeUploadsLocation
		if [ -z "${volumeUploadsLocation}" ]; then
			volumeUploadsLocation=${tmpVolumeUploadsLocation}
		fi
		if [ -z "${volumeUploadsLocation}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			volumeUploadsLocationOK=true
		fi
	done

	# Location of config folder, linked to API and Administration
	defaultVolumeConfigLocation=${defaultVolumeLocation}"/config"
	volumeConfigLocationOK=false

	while [ ${volumeConfigLocationOK} = false ]; do
		if [ -z "${volumeConfigLocation}" ]; then
			tmpVolumeConfigLocation=${defaultVolumeConfigLocation}
		else
			tmpVolumeConfigLocation=${volumeConfigLocation}
		fi

		echo -n "Veuillez entrer le path complet du volume accocié au dossier uploads de votre site ["${tmpVolumeConfigLocation}"] : "
		read volumeConfigLocation
		if [ -z "${volumeConfigLocation}" ]; then
			volumeConfigLocation=${tmpVolumeConfigLocation}
		fi
		if [ -z "${volumeConfigLocation}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			volumeConfigLocationOK=true
		fi
	done

	# Location of volume associated to MySQL Server container
	defaultVolumeMySQLLocation="$defaultVolumeLocation/database"
	volumeMySQLLocationOK=false

	while [ ${volumeMySQLLocationOK} = false ]; do
		if [ -z "${volumeMySQLLocation}" ]; then
			tmpVolumeMySQLLocation=${defaultVolumeMySQLLocation}
		else
			tmpVolumeMySQLLocation=${volumeMySQLLocation}
		fi

		echo -n "Veuillez entrer le chemin complet du volume accocié à la base de données de votre site ["${tmpVolumeMySQLLocation}"] : "
		read volumeMySQLLocation
		if [ -z "${volumeMySQLLocation}" ]; then
			volumeMySQLLocation=${tmpVolumeMySQLLocation}
		fi
		if [ -z "${volumeMySQLLocation}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
			volumeMySQLLocationOK=true
		fi
	done

	# Root password for MySQL Server container
	passwordMySQLOK=false
	while [ ${passwordMySQLOK} = false ]; do
	    echo "Veuillez entrer votre mot de passe pour l'utilisateur root de votre base de données."
	    echo -n "Attention, pour plus de sécurité, le mot de passe doit faire au moins 8 caractères et contenir au moins"\
	         "une lettre majuscule, une lettre minuscule et un chiffre : "
        read passwordMySQL

        if [ -z "${passwordMySQL}" ]; then
			echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
		else
		    if [[ "${passwordMySQL}" =~ [a-z] ]] && [[ "${passwordMySQL}" =~ [A-Z] ]] && \
		        [[ "${passwordMySQL}" =~ [0-9] ]] && [ ${#passwordMySQL} -ge 8 ]; then
                echo -n "Veuillez taper votre mot de passe une seconde fois pour la vérification : "
                read passwordMySQLVerif
                if [ -z "${passwordMySQLVerif}" ]; then
                    echo -e "Une "${RED}"erreur"${NORMAL}" s'est produite. Veuillez recommencer."
                else
                    if [ ${passwordMySQLVerif} = ${passwordMySQL} ]; then
                        passwordMySQLOK=true;
                    else
                        echo -e ${RED}"Erreur:"${NORMAL}" Les mots de passe entrés ne correspondent pas. Veuillez recommencer."
                    fi
                fi
            else
                echo -e ${RED}"Erreur:"${NORMAL}" Votre mot de passe doit faire au moins 8 caractères et contenir au moins "\
                        "une lettre majuscule, une lettre minuscule et un chiffre. Veuillez recommencer."
            fi
		fi
    done

	# Prefix name for project containers
	containerNamePrefixOK=false

	while [ ${containerNamePrefixOK} = false ]; do
		if [ -z "${containerNamePrefix}" ]; then
			tmpContainerNamePrefix=${defaultContainerNamePrefix}
		else
			tmpContainerNamePrefix=${containerNamePrefix}
		fi

		echo -n "Veuillez entrer le préfixe que désirez pour chaque nom de vos dockers (<prefix>-admin, <prefix>-api, etc.) ["${tmpContainerNamePrefix}"] : "
		read containerNamePrefix
		if [ -z "${containerNamePrefix}" ]; then
			containerNamePrefix=${tmpContainerNamePrefix}
		fi
		if [ -z "${containerNamePrefix}" ]; then
			echo "Une erreur s'est produite. Veuillez recommencer."
		else
			containerNamePrefixOK=true
		fi
	done

    # Name of the website
    websiteNameOK=false

	while [ ${websiteNameOK} = false ]; do
		if [ -z "${websiteName}" ]; then
			tmpWebsiteName=${defaultWebsiteName}
		else
			tmpWebsiteName=${websiteName}
		fi

		echo -n "Veuillez entrer nom de votre site web ["${tmpWebsiteName}"] : "
		read websiteName
		if [ -z "${websiteName}" ]; then
			websiteName=${tmpWebsiteName}
		fi
		if [ -z "${websiteName}" ]; then
			echo "Une erreur s'est produite. Veuillez recommencer."
		else
			websiteNameOK=true
		fi
	done

	# Country code
    countryOK=false

	while [ ${countryOK} = false ]; do
		if [ -z "${country}" ]; then
			tmpCountry=${defaultCountry}
		else
			tmpCountry=${country}
		fi

		echo -n "Veuillez entrer le code de votre pays (code de 2 lettres) ["${tmpCountry}"] : "
		read country
		if [ -z "${country}" ]; then
			country=${tmpCountry}
		fi
		if [ -z "${country}" ]; then
			echo "Une erreur s'est produite. Veuillez recommencer."
		else
		    if [ ${#country} -eq 2 ]; then
			    countryOK=true
            else
                echo -e ${RED}"Erreur: "${NORMAL}"Le code doit être composé de 2 lettres. Veuillez recommencer."
            fi
		fi
	done

	# City name
    cityOK=false

	while [ ${cityOK} = false ]; do
		if [ -z "${city}" ]; then
			tmpCity=${defaultCity}
		else
			tmpCity=${city}
		fi

		echo -n "Veuillez entrer le nom de votre ville ["${tmpCity}"] : "
		read city
		if [ -z "${city}" ]; then
			city=${tmpCity}
		fi
		if [ -z "${city}" ]; then
			echo "Une erreur s'est produite. Veuillez recommencer."
		else
			cityOK=true
		fi
	done

    echo "Voici votre configuration de votre infrastructure Docker. Merci de confirmer celle-ci plus bas."
	echo ""
	echo "-------------------------------------------------------------"
	echo ""
	echo -e "Nom de votre site : "${BOLD}${websiteName}${NORMAL}
	echo ""
	echo -e "Nom de domaine de votre site : "${BOLD}${websiteDomain}${NORMAL}
	echo -e "Nom de domaine de votre API : "${BOLD}${APIDomain}${NORMAL}
	echo -e "Nom de domaine de votre administration : "${BOLD}${adminDomain}${NORMAL}
	echo -e "Nom de domaine de phpMyAdmin : "${BOLD}${phpmyadminDomain}${NORMAL}
	echo ""
	echo "Informations nécessaires pour le certificat HTTPS (Let's Encrypt) :"
	echo -e "    - Adresse email : "${BOLD}${emailAddress}${NORMAL}
	echo -e "    - Code pays : "${BOLD}${country}${NORMAL}
	echo -e "    - Nom de la ville : "${BOLD}${city}${NORMAL}
	echo ""
	echo "Chemin des volumes nécessaires à votre infrastructure Docker"
	echo -e "    - Volume lié au dossier \"uploads\" : "${BOLD}${volumeUploadsLocation}${NORMAL}
	echo -e "    - Volume lié au dossier \"config\" : "${BOLD}${volumeConfigLocation}${NORMAL}
	echo -e "    - Volume lié au dossier base de données : "${BOLD}${volumeMySQLLocation}${NORMAL}
	echo ""
	echo -e "Mot de passe de l'utilisateur root pour la base de données du projet : "${BOLD}${passwordMySQL}${NORMAL}
	echo -e "Préfixe du nom de vos containers (<prefix>-admin, <prefix>-api, etc.) : "${BOLD}${containerNamePrefix}${NORMAL}
	echo ""
	echo "-------------------------------------------------------------"
	read -p "Confirmez-vous la configuration de votre infrastructure Docker ? ([O]ui ou [N]on) : " -n1 isGoodConfig
	echo ""
	while [ "${isGoodConfig}" != "n" ] && [ "${isGoodConfig}" != "N" ] && [ "${isGoodConfig}" != "o" ] && [ "${isGoodConfig}" != "O" ]; do
		echo "Erreur ! Veuillez répondre [O]ui ou [N]on"
		read -p "Confirmez-vous la configuration de votre infrastructure Docker ? ([O]ui ou [N]on) : " -n1 isGoodConfig
		echo ""
	done
	if [ "${isGoodConfig}" == "n" ] || [ "${isGoodConfig}" == "N" ]; then
		echo "C'est entendu. Merci de recommencer la configuration de votre infrastructure Docker."
	fi
done

# Backup of the configuration
echo -ne "Sauvegarde de vos choix dans $installConfigFilePath...\r"

if [ ! -d "${installDirPath}" ];then
    mkdir -p ${installDirPath}
else
    if [ -f "${installConfigFilePath}" ]; then
        rm ${installConfigFilePath}
    fi
fi
touch ${installConfigFilePath}
chmod +x ${installConfigFilePath}


cat <<-EOF > ${installConfigFilePath}
# Fichier de configuration pour les containers MySQL, PHP et NodeJS
# Noms de domaine
websiteDomain=${websiteDomain}
APIDomain=${APIDomain}
adminDomain=${adminDomain}
phpmyadminDomain=${phpmyadminDomain}

# Variables pour le certificat HTTPS
websiteName="${websiteName}"
emailAddress="${emailAddress}"
country="${country}"
city="${city}"

# Chemins des volumes liés
volumeUploadsLocation=${volumeUploadsLocation}
volumeConfigLocation=${volumeConfigLocation}
volumeMySQLLocation=${volumeMySQLLocation}
volumeFileManifestClient=${manifestClientPath}

# Préfixe du nom des containers
containerNamePrefix=${containerNamePrefix}

# Mot de passe root pour la base de données
passwordMySQL=${passwordMySQL}

EOF

echo -ne "Sauvegarde de vos choix dans $installConfigFilePath... "${GREEN}"fait"${NORMAL}"\r"
echo ""

# Creation of client site manifest
echo -ne "Création du manifest pour le site client dans $manifestClientPath...\r"

if [ -f "${manifestClientPath}" ]; then
    rm ${manifestClientPath}
fi

touch ${manifestClientPath}

cat <<-EOF > ${manifestClientPath}
{
    "name": "${websiteName}",
    "short_name": "template",
    "icons": [
        {
            "src": "assets/images/touch/icon-128x128.png",
            "sizes": "128x128",
            "type": "image/png"
        },
        {
            "src": "assets/images/touch/apple-touch-icon.png",
            "sizes": "152x152",
            "type": "image/png"
        },
        {
            "src": "assets/images/touch/ms-touch-icon-144x144-precomposed.png",
            "sizes": "144x144",
            "type": "image/png"
        },
        {
            "src": "assets/images/touch/chrome-touch-icon-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        }
    ],
    "start_url": "/index.html",
    "api_url": "${APIDomain}/v1",
    "display": "standalone",
    "background_color": "#70DBA2",
    "theme_color": "#656970"
}
EOF

echo -ne "Création du manifest pour le site client dans $manifestClientPath... "${GREEN}"fait"${NORMAL}"\r"
echo ""

# Backup of the configuration to generate certificate for JWT tokens
echo -ne "Sauvegarde de votre configuration pour le certificat HTTPS...\r"

if [ ! -d ${volumeConfigLocation}"/ssl" ];then
    mkdir -p ${volumeConfigLocation}/ssl
else
    if [ -f ${volumeConfigLocation}"/ssl/"${openSSLFileConfig} ]; then
        rm ${volumeConfigLocation}"/ssl/"${openSSLFileConfig}
    fi
fi
touch ${volumeConfigLocation}"/ssl/"${openSSLFileConfig}
chmod +x ${volumeConfigLocation}"/ssl/"${openSSLFileConfig}

printf "# Fichier de configuration générer le certificat HTTPS pour le token JWT\n" > "$volumeConfigLocation/ssl/$openSSLFileConfig"
printf "subject='/C="${country}"/ST="${city}"/L="${city}"/O="${websiteName}"/CN="${websiteDomain}"/emailAddress="${emailAddress}"'" >> ${volumeConfigLocation}"/ssl/"${openSSLFileConfig}

echo -ne "Sauvegarde de votre configuration pour le certificat HTTPS... "${GREEN}"fait"${NORMAL}"\r"
echo ""

# Create configuration file for API and Administration
echo -ne "Création du fichier de configuration pour l'API et l'administration...\r"
if [ ! -f ${volumeConfigLocation}/config.php ];then
    touch ${volumeConfigLocation}/config.php
cat <<-EOF > ${volumeConfigLocation}/config.php
<?php
/**
 * Voici les détails de connexion à la base de données
 */
define("DB_HOST", "db");
define("DB_USER", "root");
define("DB_PASSWORD", "${passwordMySQL}");
define("DB_DATABASE", "virtual_gallery");

define("ERROR_REPORTING_DISABLED", 0, true);
define("ERROR_REPORTING_ERR_WARN_PARSE", E_ERROR | E_WARNING | E_PARSE, true);
define("ERROR_REPORTING_ERR_WARN_PARSE_NOTICE", E_ERROR | E_WARNING | E_PARSE | E_NOTICE, true);
define("ERROR_REPORTING_ALL_EXCEPT_NOTICE", E_ALL & E_NOTICE, true);
define("ERROR_REPORTING_ALL", E_ALL, true);
define("ERROR_REPORTING_LEVEL", ERROR_REPORTING_ALL, true);

define("API_URL", "$APIDomain", true);
define("SITE_TITLE", "$websiteName", true);
define("SITE_URL", "$websiteDomain", true);
define("ADMIN_URL", "$adminDomain", true);

define("JWT_PRIVATE_KEY", file_get_contents("/var/www/config/ssl/jwt.key"), true);
define("JWT_CERTIFICATE", file_get_contents("/var/www/config/ssl/jwt.csr"), true);

?>
EOF
fi
echo -ne "Création du fichier de configuration pour l'API et l'administration... "${GREEN}"fait"${NORMAL}"\r"
echo ""

sleep 2
projectConfig=1
