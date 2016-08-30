<?php
//session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');

$ini = parse_ini_file("../assets/config/conf.ini.php", true);
$iniMsg = parse_ini_file($ini['path']['confMsgFR'], true);
$iniLang = json_decode(file_get_contents("../assets/translations/fr.json"), true);
$sql = json_decode(file_get_contents("../assets/config/sqlRequests.json"), true);

include_once("../config/config.php");

include($ini["path"]["functions"]);
include($ini["path"]["functionsSQL"]);
require "../vendor/autoload.php";

if (!empty($_SERVER["HTTP_AUTHORIZATION"])) {
    list($jwt) = sscanf(htmlspecialchars($_SERVER["HTTP_AUTHORIZATION"]), 'Bearer %s');
    if($userInfos = verifyJWT($jwt)) {
        if (!empty($userInfos->data->userId) && !empty($userInfos->data->username) && !empty($userInfos->data->displayedName)
            && !empty($userInfos->data->isAdmin) && isset($userInfos->data->isSuperAdmin)) {
            define("USER_USERID", htmlspecialchars($userInfos->data->userId));
            define("USER_USERNAME", htmlspecialchars($userInfos->data->username));
            define("USER_DISPLAYED_NAME", htmlspecialchars($userInfos->data->displayedName));
            define("USER_IS_ADMIN", empty(htmlspecialchars($userInfos->data->isAdmin))?"false":htmlspecialchars($userInfos->data->isAdmin));
            define("USER_IS_SUPER_ADMIN", empty(htmlspecialchars($userInfos->data->isSuperAdmin))?"false":htmlspecialchars($userInfos->data->isSuperAdmin));

            $db = connectDB(DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD);

            if (!isset($_FILES['imageInput'])) {
                header("HTTP/1.1 400 Bad Request");
                print_r($_FILES);
//                echo json_encode("No file uploaded !");
            } else {
                if (sizeof($_FILES) > 1) {
                    for ($i=0; $i < sizeof($_FILES); $i++){
                        try {
                            if (!isset($_FILES["imageInput"]["error"][$i])) {
                                throw new RuntimeException('Invalid params.');
                            }

                            // Vérification des erreurs $_FILES['upfile']['error']
                            switch ($_FILES['imageInput']['error'][$i]) {
                                case UPLOAD_ERR_OK:
                                    break;
                                case UPLOAD_ERR_NO_FILE:
                                    throw new RuntimeException('No received file.');
                                case UPLOAD_ERR_INI_SIZE:
                                case UPLOAD_ERR_FORM_SIZE:
                                    throw new RuntimeException('Max file size overloaded.');
                                default:
                                    throw new RuntimeException('Unknown error.');
                            }

                            // Aucune confiance en $_FILES['upfile']['mime'] ...
                            $finfo = new finfo(FILEINFO_MIME_TYPE);
                            if (false === $ext = array_search(
                                    $finfo->file($_FILES['imageInput']['tmp_name'][$i]),
                                    array(
                                        'jpg' => 'image/jpeg',
                                        'png' => 'image/png',
                                        'gif' => 'image/gif',
                                    ),
                                    true
                                )) {
                                throw new RuntimeException('This file format is not authorized.');
                            }

                            // Sauvegarde du fichier
                            $tempName = sha1_file($_FILES['imageInput']['tmp_name'][$i]);
                            if (!move_uploaded_file(
                                $_FILES['imageInput']['tmp_name'][$i],
                                sprintf('uploads/%s.%s',
                                    $tempName,
                                    $ext
                                )
                            )) {
                                throw new RuntimeException('Error during upload. Please retry.');
                            }
                            // Upload bdd
                            if (sendSQLReq($db, $sql['file']['uploadFile'],
                                array(":file_name"=> $_FILES['imageInput']['name'][$i],
                                    ":server_file_name" => $tempName.".".$ext,
                                    ":mime" => $ext,
                                    ":upload_date" => date('Y-m-d H:i:s'),
                                    ":user_id" => USER_USERID))) {

                                header("HTTP/1.1 200 OK");
                                echo json_encode(
                                    array("msg"=>"Votre fichier a bien été uploadé sur le serveur.",
                                        "link"=>"/uploads/".$tempName.".".$ext,
                                        "id"=>$db -> lastInsertId())
                                );
                            } else {
                                header("HTTP/1.1 400 Bad Request");
                                echo json_encode(array("msg"=>"Un problème est survenu."));
                            }




                        } catch (RuntimeException $e) {
                            header("HTTP/1.1 400 Bad Request");
                            echo json_encode("Erreur : " . $e->getMessage());
                        }
                    }
                } elseif (sizeof($_FILES) == 1) {
                    try {
                        if (!isset($_FILES["imageInput"]["error"])) {
                            throw new RuntimeException('Invalid params.');
                        }

                        // Vérification des erreurs $_FILES['upfile']['error']
                        switch ($_FILES['imageInput']['error']) {
                            case UPLOAD_ERR_OK:
                                break;
                            case UPLOAD_ERR_NO_FILE:
                                throw new RuntimeException('No received file.');
                            case UPLOAD_ERR_INI_SIZE:
                            case UPLOAD_ERR_FORM_SIZE:
                                throw new RuntimeException('Max file size overloaded.');
                            default:
                                throw new RuntimeException('Unknown error.');
                        }

                        // Aucune confiance en $_FILES['upfile']['mime'] ...
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        if (false === $ext = array_search(
                                $finfo->file($_FILES['imageInput']['tmp_name']),
                                array(
                                    'jpg' => 'image/jpeg',
                                    'png' => 'image/png',
                                    'gif' => 'image/gif',
                                ),
                                true
                            )) {
                            throw new RuntimeException('This file format is not authorized.');
                        }

                        // Sauvegarde du fichier
                        $tempName = sha1_file($_FILES['imageInput']['tmp_name']);
                        if (!move_uploaded_file(
                            $_FILES['imageInput']['tmp_name'],
                            sprintf('uploads/%s.%s',
                                $tempName,
                                $ext
                            )
                        )) {
                            throw new RuntimeException('Error during upload. Please retry.');
                        }
                        // Upload bdd
                        if (sendSQLReq($db, $sql['file']['uploadFile'],
                            array(":file_name"=> $_FILES['imageInput']['name'],
                                ":server_file_name" => $tempName.".".$ext,
                                ":mime" => $ext,
                                ":upload_date" => date('Y-m-d H:i:s'),
                                ":user_id" => USER_USERID))) {

                            header("HTTP/1.1 200 OK");

                            echo json_encode(
                                array("msg"=>"Votre fichier a bien été uploadé sur le serveur.",
                                    "link"=>"/uploads/".$tempName.".".$ext,
                                    "id"=>$db -> lastInsertId())
                            );
                        } else {
                            header("HTTP/1.1 400 Bad Request");
                            echo json_encode(array("msg"=>"Un problème est survenu."));
                        }


                    } catch (RuntimeException $e) {
                        header("HTTP/1.1 400 Bad Request");
                        echo json_encode("Erreur : " . $e->getMessage());
                    }
                }
            }
        }
    } else {
        header("HTTP/1.1 401 Unauthorized");
        echo json_encode("Erreur : Le token est incorrect.");
    }
} else {
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode("Erreur : Le token est manquant.");
}
$db = null;
?>