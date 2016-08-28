<?php
use Slim\Http\Request;
use Slim\Http\Response;
use UserController as User;

class FileController {
    private $db;
    private $sql;

    public function __construct($db, $sql) {
        $this->db = $db;
        $this->sql = $sql;
    }

    public function uploadFiles(Request $request, Response $response, Array $args) {

        $authorization = $request->getHeader("HTTP_AUTHORIZATION");
        if (!empty($authorization[0])) {
            if ($tokenInfo = User::verifyBearerAuthorization(htmlspecialchars($authorization[0]))) {
                $userId = $tokenInfo->data->userId;
//                $isAdmin = $tokenInfo->data->isAdmin;

                $file = $request->getUploadedFiles()["imageInput"];

                if (!isset($file)) {
                    $response = $response->withStatus(409);
                    $response->getBody()->write("No file uploaded !");
                } else {
                    try {
                        // Vérification des erreurs $_FILES['upfile']['error']
                        switch ($file->getError()) {
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
                        echo $file->getClientFilename();
                        print_r($file);
                        if (false === $ext = array_search(
                                $file->getClientMediaType(),
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
                        $file->moveTo("../uploads/");
//                        $tempName = sha1_file($file->getClientFilename());
//                        if (!move_uploaded_file(
//                            $file->getClientFilename(),
//                            sprintf('../uploads/%s.%s',
//                                $tempName,
//                                $ext
//                            )
//                        )) {
//                            throw new RuntimeException('Error during upload. Please retry.');
//                        }
                        // Upload bdd
                        sendSQLReq($this->db, $this->sql['file']['uploadFile'],
                            array(":file_name"=> $file->getClientFilename(),
                                ":server_file_name" => $tempName.".".$ext,
                                ":mime" => $ext,
                                ":upload_date" => date('Y-m-d H:i:s'),
                                ":user_id" => $userId));

                        $response = $response->withStatus(200);
                        $response->getBody()->write(json_encode(
                                array("msg"=>"Votre fichier a bien été uploadé sur le serveur.",
                                "link"=>"/uploads/".$tempName.".".$ext,
                                "id"=>$this->db -> lastInsertId()))
                        );
                    } catch (RuntimeException $e) {
                        $response = $response->withStatus(400);
                        $response->getBody()->write("Erreur : " . $e->getMessage());
                    }






//                    $response = $response->withStatus(200);
//                    $response->getBody()->write("File uploaded!!!");
                    return $response;

//                    $imgs = array();
//
//                    $files = $_FILES['uploads'];
//                    $cnt = count($files['name']);
//
//                    for ($i = 0; $i < $cnt; $i++) {
//                        if ($files['error'][$i] === 0) {
//                            $name = uniqid('img-' . date('Ymd') . '-');
//                            if (move_uploaded_file($files['tmp_name'][$i], '../uploads/' . $name) === true) {
//                                $imgs[] = array('url' => '../uploads/' . $name, 'name' => $files['name'][$i]);
//                            }
//
//                        }
//                    }
//
//                    $imageCount = count($imgs);
//
//                    if ($imageCount == 0) {
//                        echo 'No files uploaded!!  <p><a href="/">Try again</a>';
//                        return;
//                    }
//
//                    $plural = ($imageCount == 1) ? '' : 's';
//
//                    foreach ($imgs as $img) {
//                        printf('%s <img src="%s" width="50" height="50" /><br/>', $img['name'], $img['url']);
//                    }
                }

            }
        }


        return $response;
    }

    public function getPageUploads (Request $request, Response $response, Array $args) {
        return $this->renderer->render($response, '../includes/templates/upload.phtml', $args);
    }
}

?>