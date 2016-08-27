<?php
use Slim\Http\Request;
use Slim\Http\Response;
use UserController as User;


class CreationController {
    private $db;
    private $sql;

    public function __construct($db, $sql) {
        $this->db = $db;
        $this->sql = $sql;
    }


    /**
     * @api {post} /creations Add a new Creation
     * @apiName AddCreation
     * @apiVersion 1.0.0
     * @apiGroup Creation
     *
     * @apiPermission admin
     * @apiUse AuthorizationHeader
     *
     * @apiParam {String} name Name of the creation.
     * @apiParam {String} [shortDescription] Short description of the Creation.
     * @apiParam {String} [longDescription] Long description of the Creation.
     * @apiParam {String} [creationType] Type of the Creation.
     * @apiParam {Number[]} [images] List of Creation images (Array of Numbers).
     *
     * @apiSuccess {Number} id The new Creations <code>id</code>.
     *
     * @apiError NoAccessRight Only authenticated Admins can access the data.
     * @apiError MissingName Parameter name is not present.
     */
    public function addCreation(Request $request, Response $response, Array $args) {
        $post = $request->getParsedBody();
        $authorization = $request->getHeader("HTTP_AUTHORIZATION");

        if (!empty($authorization[0])) {
            if ($tokenInfo = User::verifyBearerAuthorization(htmlspecialchars($authorization[0]))) {
                $userId = $tokenInfo->data->userId;

                if (!empty($post["name"])) {
                    $name = htmlspecialchars($post["name"]);
                    $shortDescription = empty($post["shortDescription"]) ? NULL : htmlspecialchars($post["shortDescription"]);
                    $longDescription = empty($post["longDescription"]) ? NULL : htmlspecialchars($post["longDescription"]);
                    $creationType = empty($post["creationType"]) ? NULL : htmlspecialchars($post["creationType"]);
                    if (!empty($post["images"])) {
                        for ($i=0; $i < sizeof($post["images"]); $i++) {
                            $images[$i] = htmlspecialchars($post["images"][$i]);
                        }
                    } else {
                        $images = NULL;
                    }

                    if (sendSQLReq($this->db, $this->sql["creation"]["insert"]["addCreation"],
                        array(":name" => $name,
                            ":shortDescription" => $shortDescription,
                            ":longDescription" => $longDescription,
                            ":creationType" => $creationType,
                            ":userId" => $userId))) {

                        if ($creationId = $this->db->lastInsertId()) {
                            $response = $response->withStatus(200);
                            $response->getBody()->write(json_encode(array("id" => $creationId)));
                        } else {
                            $response = $response->withStatus(500);
                            $response->getBody()->write(json_encode(array("error" => "ServerError")));
                        }
                    } else {
                        $response = $response->withStatus(500);
                        $response->getBody()->write(json_encode(array("error" => "ServerError")));
                    }

                } else {
                    $response->getBody()->write(json_encode(array("error" => "MissingName")));
                }
            } else {
                $response = $response->withStatus(409);
                $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));
            }
        } else {
            $response = $response->withStatus(409);
            $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));
        }

        return $response;
    }

    /**
     * @api {get} /creations/:id Request Creation information
     * @apiName GetCreation
     * @apiVersion 1.0.0
     * @apiGroup Creation
     *
     * @apiParam {Number} id Creations unique ID.
     *
     * @apiSuccess {String} name Name of the Creation.
     * @apiSuccess {String} shortDescription Short description of the Creation.
     * @apiSuccess {String} longDescription Long description of the Creation.
     * @apiSuccess {String} creationType Type of the Creation.
     * @apiSuccess {number} id Id of the Creation.
     * @apiSuccess {String} author Name of the User.
     * @apiSuccess {number} authorId Id of the Author.
     * @apiSuccess {Object[]} images Information about images linked to the Creation (Array of Objects).
     * @apiSuccess {String} options.fileName Name of the image.
     * @apiSuccess {String} options.uploadDate Upload date of the image.
     * @apiSuccess {String} options.serverFileName Server name of the image.
     *
     * @apiError CreationNotFound The <code>id</code> of the Creation was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "CreationNotFound"
     *     }
     */
    public function getCreation(Request $request, Response $response, Array $args) {
        if (!empty($args["id"])) {
            $creationId = htmlspecialchars($args["id"]);

            if ($creationInfos = fetchSQLReq($this->db, $this->sql["creation"]["select"]["selectProductInfosWithImages"],
                array(":id"=> $creationId), false, true)) {

                $images = json_decode($creationInfos["images"]);
                $creationInfos["images"] = [];

                for ($i=0; $i < sizeof($images); $i++) {
                    $creationInfos["images"][$i] = fetchSQLReq($this->db, $this->sql["file"]["select"]["selectImageInfo"],
                        array(":id" => $images[$i]), false, true);
                }
                $response = $response->withStatus(200);
                $response->getBody()->write(json_encode($creationInfos));
            } else {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(array("error" => "CreationNotFound")));
            }
        } else {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array("error" => "ServerError")));
        }

        return $response;
    }

    /**
     * @api {get} /creations Request last Creations
     * @apiName GetCreations
     * @apiVersion 1.0.0
     * @apiGroup Creation
     *
     * @apiSuccess {String} name Name of the creation
     *
     */

    public function getCreations(Request $request, Response $response, Array $args) {
        $sqlReq = $this->sql["creation"]["select"]["selectLastCreations"];
        $reqParams = $request->getQueryParams();
        if (array_key_exists ("limit",$reqParams))
            $sqlReq = $sqlReq . " LIMIT " . htmlspecialchars($reqParams["limit"]);

        try {
            $stmt = $this->db->prepare($sqlReq);
            $stmt->execute();
            $result = $stmt->fetchAll();

            $i = 0;
            foreach ($result as $content) {
                $data[$i]["name"] = $content["name"];
                $data[$i]["shortDescription"] = $content["shortDescription"];
                $data[$i]["author"] = $content["author"];
                $data[$i]["id"] = $content["id"];
                $data[$i]["authorId"] = $content["authorId"];
                $imageId = $content["images"][0];
                $data[$i]["image"] = fetchSQLReq($this->db, $this->sql["file"]["select"]["selectImageInfo"],
                                                    array(":id"=>$imageId), false,true);

                $i++;
            }
//            $data = array("data"=>$result);
            $response->getBody()->write(json_encode($data));
            $response = $response->withStatus(200);
        } catch (Exception $e) {
            $response = $response->withStatus(409);
            $response->getBody()->write("");
        }

        return $response;
    }

    /**
     * @api {get} /users/:id/creations Request user's last Creations
     * @apiName getAuthorCreations
     * @apiVersion 1.0.0
     * @apiGroup Creation
     *
     * @apiParam {Number} id Users unique ID.
     * @apiParam {Number} [limit] Limit of received creations
     *
     * @apiParamExample {Number} Limit usage example:
     *  /v1/users/:id/creations?limit=20
     *
     * @apiSuccess {String} name Name of the creation
     *
     */
    public function getAuthorCreations(Request $request, Response $response, Array $args) {

    }

    /**
     * @api {get} /creations/:id/author Request author of Creation
     * @apiName getCreationAuthor
     * @apiVersion 1.0.0
     * @apiGroup Creation
     *
     * @apiParam {Number} id Creations unique ID.
     *
     * @apiSuccess {Number} userId Users unique ID.
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *        "userId": 2
     *      }
     *
     */
    public function getCreationAuthor(Request $request, Response $response, Array $args) {

    }


}

?>