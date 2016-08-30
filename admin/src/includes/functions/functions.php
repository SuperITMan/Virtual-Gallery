<?php
/**
 * Functions file
 * Version 0.1
 * Author : Alexis GEORGES
 */

use \Firebase\JWT\JWT;

function user_connection_admin ($login="", $password="", $rememberMe=false) {
    global $db;
    global $sql;

    if ($userId = userPasswordVerify($login, $password)) {
        // Get user infos
        $userInfos = fetchSQLReq($db, $sql["user"]["select"]["basicInfosFromId"],
            array(":id" => $userId), false, true);

        $payload = array(
            "iss" => SITE_URL,
            "iat" => time(),
            "nbf" => time(),
            "data" => [
                "userId" => $userInfos["id"],
                "username" => $userInfos["username"],
                "isAdmin" => $userInfos["isAdmin"]?"true":"false",
                "isSuperAdmin" => $userInfos["isSuperAdmin"]?"true":"false",
                "displayedName" => $userInfos["displayedName"]
            ]
        );
        $_SESSION["token"] = JWT::encode($payload, JWT_PRIVATE_KEY, 'RS256');

        return true;
    }
    return false;
}

function userPasswordVerify($login="", $password="") {
    global $db;
    global $sql;

    $login = filter_var($login, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $dbPassword = fetchSQLReq($db, $sql["user"]["select"]["passwordFromLogin"], array(":username" => $login, ":email" => $login), true);

    if (!$dbPassword) {
        // User does not exist
        return false;
    } elseif (password_verify($password, $dbPassword)) {
        return fetchSQLReq($db, $sql["user"]["select"]["idFromLogin"], array(":username" => $login, ":email" => $login), true);
    } else {
        return false;
    }
}

function hashPassword($pwd){
    $options = [
        'cost' => 8,
    ];
    return password_hash($pwd, PASSWORD_DEFAULT, $options);
}

function getAllUsers() {
    global $db;
    global $sql;

    if (isAdmin()) {
        $stmt = $db -> prepare($sql["user"]["select"]["allUsersBasicInfos"]);
        $stmt -> execute();
        return $stmt -> fetchAll();
    }
    return false;
}

function getAllCreations() {
    global $db;
    global $sql;

    if (isAdmin()) {
        return fetchSQLReq($db,$sql["creation"]["select"]["allUserCreationsNameShortDescDateTypeId"],
            array(":userId"=>USER_USERID));
    }
    return false;
}

function getCreation($id) {
    global $db;
    global $sql;

    $id = htmlspecialchars($id);

    if (isAdmin()) {
        $data = fetchSQLReq($db, $sql["creation"]["select"]["creationInfo"], array(":id"=>$id), false, true);
        if ($data["authorId"] == USER_USERID) {
            $imageIds = json_decode(htmlspecialchars_decode($data["imageIds"]));
            if (!empty($imageIds)) {
                if (sizeof($imageIds) > 1) {
                    $data["imageIds"] = implode(",",$imageIds);
                } else {
                    $data["imageIds"] = $imageIds[0];
                }

                $id = 0;
                foreach ($imageIds as $imageId) {
                    $data["images"][$id] = fetchSQLReq($db, $sql["file"]["select"]["selectImageInfo"], array(":id"=>$imageId), false, true);
                    $id++;
                }
            } else
                $data["imageIds"] = "";

            if (!empty($data["usedMaterials"]))
                $data["usedMaterials"] = implode(",",json_decode(htmlspecialchars_decode($data["usedMaterials"])));
            else
                $data["usedMaterials"] = "";

            return $data;
        }
        return false;
    } return false;
}

function deleteCreation($id) {
    global $db;
    global $sql;
    global $iniLang;

    $id = htmlspecialchars($id);

    if (isAdmin()) {
        if ($userId = fetchSQLReq($db, $sql["creation"]["select"]["creationAuthorId"], array(":id"=>$id), true)) {
            if ($userId == USER_USERID || isSuperAdmin()) {
                if (sendSQLReq($db, $sql["creation"]["delete"]["creationsMeta"], array(":creationId"=>$id))) {
                    if (sendSQLReq($db, $sql["creation"]["delete"]["creation"], array(":id"=>$id))) {
                        displaySuccessMessage(str_replace(array("%href%","%all_things%"),
                            array("index.php?p=creations&c=all", $iniLang["CREATIONS"]["ALL_CREATIONS"]),
                            $iniLang["SUCCESS_MESSAGES"]["DELETED_OK"]));
                        return true;
                    } else {
                        displayErrorMessage($iniLang["ERROR_MESSAGES"]["ERROR_DURING_DELETING"]);
                        return false;
                    }
                } else {
                    displayErrorMessage($iniLang["ERROR_MESSAGES"]["ERROR_DURING_DELETING"]);
                    return false;
                }
            } else {
                displayErrorMessage($iniLang["ERROR_MESSAGES"]["NO_RIGHT_TO_DELETE_CREATION"]);
                return false;
            }
        } else {
            displayErrorMessage($iniLang["ERROR_MESSAGES"]["CREATION_TO_DELETE_NOT_EXISTS"]);
            return false;
        }
    } else {
        displayErrorMessage($iniLang["ERROR_MESSAGES"]["NO_RIGHT_TO_DELETE_CREATION"]);
        return false;
    }
}

function isAdmin() {
    return (strcmp(USER_IS_ADMIN,"true")==0);
}

function isSuperAdmin() {
    return (strcmp(USER_IS_SUPER_ADMIN,"true")==0);
}

function verifyJWT ($jwt="") {
    try {
        $decoded = JWT::decode($jwt, JWT_CERTIFICATE, array("RS256"));
        return $decoded;
    } catch (BeforeValidException $e) {
        return ERROR_REPORTING_LEVEL ? "Error : " . $e->getMessage() : false;
    } catch (SignatureInvalidException $e) {
        return ERROR_REPORTING_LEVEL ? "Error : " . $e->getMessage() : false;
    } catch (UnexpectedValueException $e) {
        return ERROR_REPORTING_LEVEL ? "Error : " . $e->getMessage() : false;
    } catch (Exception $e) {
        return ERROR_REPORTING_LEVEL ? "Error : " . $e->getMessage() : false;
    }
}

function editCreation ($postData = [], $creationId) {
    global $db;
    global $sql;
    global $iniLang;

    $isEdited = false;

    $creationId = htmlspecialchars($creationId);
    $creation = getCreation($creationId);
//TODO afficher message succès pour chaque update...
//    print_r()
    if (isAdmin()) {
        if ($userId = fetchSQLReq($db, $sql["creation"]["select"]["creationAuthorId"], array(":id"=>$creationId), true)) {
            if ($userId == USER_USERID || isSuperAdmin()) {
                $creationName = htmlspecialchars($postData["creationName"]);
                if (strcmp($creation["name"], $creationName) != 0) {
                    sendSQLReq($db, $sql["creation"]["update"]["name"], array(":name"=>$creationName,":id"=>$creationId));
                    $isEdited = true;
                }

                $creationType = htmlspecialchars($postData["creationType"]);
                if (strcmp($creation["creationType"], $creationType) != 0) {
                    sendSQLReq($db, $sql["creation"]["update"]["creationType"], array(":creationType"=>$creationType,":id"=>$creationId));
                    $isEdited = true;
                }

                $imagesIds = htmlspecialchars($postData["imagesIds"]);
                if (strcmp($creation["imageIds"], $imagesIds) != 0) {
                    $imagesIds = json_encode(explode(",", $imagesIds));
                    sendSQLReq($db, $sql["creation"]["update"]["imagesIds"], array(":imagesIds"=>$imagesIds,":id"=>$creationId));
                    $isEdited = true;
                }

                $shortDescription = empty($postData["shortDescription"])?$creation["shortDescription"]:htmlspecialchars($postData["shortDescription"]);
                if (strcmp($creation["shortDescription"], $shortDescription) != 0) {
                    sendSQLReq($db, $sql["creation"]["update"]["shortDescription"], array(":shortDescription"=>$shortDescription,":id"=>$creationId));
                    $isEdited = true;
                }

                $longDescription = empty($postData["longDescription"])?$creation["longDescription"]:htmlspecialchars($postData["longDescription"]);
                if (strcmp($creation["longDescription"], $longDescription) != 0) {
                    sendSQLReq($db, $sql["creation"]["update"]["longDescription"], array(":longDescription"=>$shortDescription,":id"=>$creationId));
                    $isEdited = true;
                }

                $usedMaterials = empty($postData["usedMaterials"])?$creation["usedMaterials"]:htmlspecialchars($postData["usedMaterials"]);
                if (strcmp($creation["usedMaterials"], $usedMaterials) != 0) {
                    sendSQLReq($db, $sql["creation"]["update"]["usedMaterials"], array(":imagesIds"=>$usedMaterials,":id"=>$creationId));
                    $isEdited = true;
                }
            } else {
                displayErrorMessage($iniLang["ERROR_MESSAGES"]["NO_RIGHT_TO_DELETE_CREATION"]);
                return false;
            }
        } else {
            displayErrorMessage($iniLang["ERROR_MESSAGES"]["CREATION_TO_DELETE_NOT_EXISTS"]);
            return false;
        }
    } else {
        displayErrorMessage($iniLang["ERROR_MESSAGES"]["NO_RIGHT_TO_DELETE_CREATION"]);
        return false;
    }
}

function addCreation($postData = []) {
    global $db;
    global $sql;

    $longDescription = empty($postData["longDescription"]) ? "" : htmlspecialchars($postData["longDescription"]);
    $shortDescription = empty($postData["shortDescription"]) ? "" : htmlspecialchars($postData["shortDescription"]);

    $usedMaterials = empty($postData["usedMaterials"]) ? "" : json_encode(explode(",", htmlspecialchars($postData["usedMaterials"])));

    $creationName = htmlspecialchars($postData["creationName"]);
    $creationType = htmlspecialchars($postData["creationType"]);

    if (!(strcmp($creationType, "BEJEWELED") == 0 || strcmp($creationType, "PAINT") == 0 || strcmp($creationType, "SCULPTURE") == 0)) {
        $creationType = "UNKNOWN";
    }

    $imagesIds = empty($postData["imagesIds"])?"":json_encode(explode(",", htmlspecialchars($postData["imagesIds"])));

    //TODO Check value of creationType
    //TODO Afficher les messages erreur ou non...
    if (!empty($creationName) && !empty($creationType) && !empty($imagesIds)) {
        $params = array(":name"=>$creationName,":shortDescription"=>$shortDescription,":longDescription"=>$longDescription,
            ":creationType"=>$creationType,":userId"=>USER_USERID, ":dateAdded"=>date("Y-m-d h:i:s"),
            ":imageIds"=>$imagesIds, ":usedMaterials"=>$usedMaterials);

//        if (substr_count($sql["creation"]["insert"]["addCreation"], ":") == count($params)) {
            if (sendSQLReq($db, $sql["creation"]["insert"]["addCreation"], $params)) {
                return $db -> lastInsertId();
            } else {
                return false;
            }
//            $stmt = $db -> prepare($sql["creation"]["insert"]["addCreation"]);
//
//            try {
//                if ($stmt->execute($params)) {
//                    $creationId = $db -> lastInsertId();
//
//                    $imagesIds = empty($imagesIds)?"":json_encode(explode(",", $imagesIds));
//                    $params = array(":creationId"=>$creationId,":metaKey"=>"images",":metaValue"=>$imagesIds);
//                    if (sendSQLReq($db,$sql["creation"]["insert"]["addMetaToCreation"],$params)) {
//
//                        $usedMaterials = empty($usedMaterials)?"":json_encode(explode(",", $usedMaterials));
//                        $params = array(":creationId"=>$creationId,":metaKey"=>"used_materials",":metaValue"=>$usedMaterials);
//                        if (sendSQLReq($db,$sql["creation"]["insert"]["addMetaToCreation"],$params)) {
//                            return $creationId;
//                        } else {
//                            return false;
//                        }
//                    } else return false;
//                } else {
//                    return false;
//                }
//            } catch (PDOException $e) {
//                echo $e->getMessage();
//                return false;
//            }
//        } else {
//            return false;
//        }
    } else {
        return false;
    }
}

//TODO Ajouter les messages en conséquence
function addUser ($postData = []) {
    global $db;
    global $sql;

    $firstName = empty($postData["firstName"]) ? "" : htmlspecialchars($postData["firstName"]);
    $lastName = empty($postData["lastName"]) ? "" : htmlspecialchars($postData["lastName"]);

    $username = htmlspecialchars($postData["username"]);
    $email = htmlspecialchars($postData["email"]);
    $password = htmlspecialchars($postData["password"]);
    $passwordConf = htmlspecialchars($postData["passwordConf"]);

    $birthDate = htmlspecialchars($postData["birthDate"]);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($passwordConf)) {
        if (strcmp($password, $passwordConf) == 0) {
            $params = array(":username" => strtolower($username), ":email" => $email, ":passwd" => $password, ":displayed_name"=>$username);

            if (sendSQLReq($db, $sql["user"]["insert"]["addUser"], $params)) {
                $userId = $db -> lastInsertId();
                $params = array(":userId" => $userId, ":firstName"=>$firstName, ":lastName"=>$lastName,
                    ":birthdayDate"=>$birthDate, ":city"=>NULL, ":country"=>NULL);

                if (sendSQLReq($db, $sql["user"]["insert"]["personalUserInfo"], $params)) {
                    return $userId;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function isPage ($pageName) {
    if(empty($_GET["p"]))
        return strcasecmp("dashboard", $pageName) == 0;
    else
        return strcasecmp(htmlspecialchars($_GET["p"]), $pageName) == 0;

}

/**
 * @param $recipient array like this (user@mail.address => "User Full Name")
 * @param $subject
 * @param $content
 */
function sendMail($recipient, $subject, $content) {
    require("../vendor/mailin-api/mailin-api-php/V2.0/Mailin.php");

//    $email = htmlspecialchars($email);
    $subject = htmlspecialchars($subject);
    $content = htmlspecialchars($content);

    $mailin = new Mailin(SENDINBLUE_API_URL,SENDINBLUE_API_KEY);
    $data = array(
        "to" => $recipient,
        "from" => array(SENDINBLUE_API_EMAIL, SENDINBLUE_API_USERNAME),
        "subject" => $subject,
        "html" => $content
//        "attachment" => array("https://domain.com/path-to-file/filename1.pdf", "https://domain.com/path-to-file/filename2.jpg")
    );

    echo "L'email est censé avoir été envoyé";
    $result = var_dump($mailin->send_email($data));
    print_r($result);

}












// Functions empruntées !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/**
 *
 * src : http://fr.wikihow.com/cr%C3%A9er-un-script-de-connexion-s%C3%A9curis%C3%A9e-avec-PHP-et-MySQL
 */
function secure_session_start() {
    $session_name = 'secure_session_id';
    $secure = SECURE;

    $httponly = true;

    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Récupère les paramètres actuels de cookies
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    session_name($session_name);
    session_start();
    session_regenerate_id();
}

function check_user_session() {
    global $db;
    global $iniSQL;

    if (isset($_SESSION["username"], $_SESSION["browserString"])) {
        $username = htmlspecialchars($_SESSION["username"]);
        $browserString = htmlspecialchars($_SESSION["browserString"]);

        $userBrowser = $_SERVER["HTTP_USER_AGENT"];

        $dbPassword = fetchSQLReq($db, $iniSQL["user"]["selectPasswordUserUsername"], array(":username" => $username), true);

        if ($dbPassword) {
            return password_verify($userBrowser.$dbPassword, $browserString);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // Nous ne voulons que les liens relatifs de $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}
?>