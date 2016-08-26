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
                "isAdmin" => $userInfos["isAdmin"],
                "isSuperAdmin" => $userInfos["isSuperAdmin"],
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

function isAdmin() {
    $infos = verifyJWT(htmlspecialchars($_SESSION["token"]));
    return true;
    return isset($infos["isAdmin"]) ? $infos["isAdmin"] : false;
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

function addUser ($postData = []) {
    global $db;
    global $sql;

    $firstName = empty($postData["firstName"]) ? "" : htmlspecialchars($postData["firstName"]);
    $lastName = empty($postData["lastName"]) ? "" : htmlspecialchars($postData["lastName"]);

    $username = htmlspecialchars($postData["username"]);
    $email = htmlspecialchars($postData["email"]);
    $password = htmlspecialchars($postData["password"]);
    $passwordConf = htmlspecialchars($postData["passwordConf"]);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($passwordConf)) {
        if (strcmp($password, $passwordConf) == 0) {
            //Prepare and check request and params
            $params = array(":username" => strtolower($username), ":email" => $email, ":passwd" => $password, ":displayed_name"=>$username);
            if (substr_count($sql["user"]["insert"]["addUser"], ":") == count($params)) {
                $stmt = $db -> prepare($sql["user"]["insert"]["addUser"]);
                try {
                    if ($stmt->execute($params)) {

                    } else {
                        return false;
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    return false;
                }

                $userId = $db -> lastInsertId();
                $params = array(":meta_value1" => $firstName, ":user_id1" => $userId, ":meta_value2" => $lastName, ":user_id2" => $userId);
                if (substr_count($sql["user"]["insert"]["firstNameAndLastNameFromId"], ":") == count($params)) {
                    $stmt = $db -> prepare($sql["user"]["insert"]["firstNameAndLastNameFromId"]);
                    try {
                        if ($stmt->execute($params)) {
                            return $userId;
                        } else {
                            return false;
                        }
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                        return false;
                    }
                } else { return false;}
            } else { return false;}
        } else { return false;}
    } else { return false;}
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