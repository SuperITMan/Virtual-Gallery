<?php
use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

class UserController {
    /**
     * @var string
     *
     * @return
     */

    private $db;
    private $sql;

    function __construct($db, $sql) {
        $this->db = $db;
        $this->sql = $sql;
    }

    /**
     * @api {post} /users Add a new User
     * @apiName CreateUser
     * @apiVersion 1.0.0
     * @apiGroup UserGroup
     *
     * @apiPermission superAdmin
     * @apiUse AuthorizationHeader
     *
     * @apiParam {String} username Username of the User.
     * @apiParam {String} email Email of the User.
     * @apiParam {String} password Password of the User.
     * @apiParam {String} displayedName Displayed name of the User.
     * @apiParam {String} [birthdayDate] List of Creation images (Array of Numbers).
     *
     * @apiSuccess {Number} id The new Users <code>id</code>.
     *
     * @apiError NoAccessRight Only authenticated Admins can access the data.
     * @apiError MissingParameter One or more parameter are not present.
     * @apiError (Error 403) EmailIncorrect Email does not respect email format. &lt;xxx@yyy.zzz&gt;
     */
    function createUser (Request $request, Response $response, Array $args) {
        $authorization = $request->getHeader("HTTP_AUTHORIZATION");
        if (!empty($authorization[0])) {
            if ($tokenInfo = User::verifyBearerAuthorization(htmlspecialchars($authorization[0]))) {
                $userId = $tokenInfo->data->userId;

                if (strcmp($tokenInfo->data->isSuperAdmin, "true") == 0) {
                    $post = $request->getParsedBody();

                    if (!empty($post["username"]) && !empty($post["email"]) && !empty($post["password"])
                        && !empty($post["displayedName"])) {

                        //TODO Add verification for createUser to be sure everything is ok
                        $username = htmlspecialchars($post["username"]);
                        $email = htmlspecialchars($post["email"]);
                        $password = htmlspecialchars($post["password"]);
                        $displayedName = htmlspecialchars($post["displayedName"]);
                        $birthdayDate = empty($post["birthdayDate"]) ? NULL : htmlspecialchars($post["birthdayDate"]);

                        if (sendSQLReq($this->db, $this->sql["user"]["insert"]["addUserWithBirthday"],
                            array(":username" => $username,
                                ":email" => $email,
                                ":passwd" => $password,
                                ":birthdayDate" => $birthdayDate))) {
                            $userId = $this->db->lastInsertId();
                            $response->getBody()->write(json_encode(array("id" => $userId)));
                        } else {
                            $response = $response->withStatus(401);
                            $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));
                        }
                    } else {
                        $response = $response->withStatus(401);
                        $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));
                    }
                } else {
                    $response = $response->withStatus(401);
                    $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));
                }
            } else {
                $response = $response->withStatus(401);
                $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));
            }
        } else {
            $response = $response->withStatus(401);
            $response->getBody()->write(json_encode(array("error" => "NoAccessRight")));

        }

        return $response;
    }

    public function getUsers (Request $request, Response $response, Array $args) {
        $sqlReq = $this->sql["user"]["select"]["allUsersBasicInfos"];
        $reqParams = $request->getQueryParams();

        if (array_key_exists ("limit",$reqParams))
        $sqlReq = $sqlReq . " LIMIT " . htmlspecialchars($reqParams["limit"]);

        try {
            $stmt = $this->db->prepare($sqlReq);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $data = array("data"=>$result);
            $response->getBody()->write(json_encode($data));
            $response = $response->withStatus(200);
        } catch (Exception $e) {
            $response = $response->withStatus(409);
            $response->getBody()->write("");
        }

        return $response;
    }

    /**
     * @api {get} /user/:id Request User information
     * @apiName GetUser
     * @apiGroup UserGroup
     * @apiVersion 0.0.1
     *
     * @apiParam {Number} id Users unique ID.
     *
     * @apiSuccess {String} firstname Firstname of the User.
     * @apiSuccess {String} lastname  Lastname of the User.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe"
     *     }
     *
     * @apiError UserNotFound The id of the User was not found.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function getUser (Request $request, Response $response, Array $args) {
        if (!empty($args["id"])) {
            $id = htmlspecialchars($args["id"]);

            $params = array(":userId" => $id);
            if ($data = fetchSQLReq($this->db, $this->sql["user"]["select"]["userStandardInfos"], $params, false, true)) {
                $response->getBody()->write(json_encode($data));
                $response = $response->withStatus(200);
            } else {
                $response->getBody()->write("Invalid id");
                $response = $response->withStatus(400);
            }
        } else {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array("error" => "ServerError")));
        }

        return $response;
    }

    /**
     * @api {post} /auth/login Authenticate a user
     * @apiName AuthenticateUser
     * @apiVersion 1.0.0
     * @apiGroup UserGroup
     *
     * @apiParam {String} login Username or email of the User.
     * @apiParam {String} password Password of the User.
     *
     * @apiSuccess {String} Authorization Authorization token for the User.
     * @apiSuccess {Number} userId Id of the User.
     * @apiSuccess {String} username Username of the User.
     * @apiSuccess {String} displayedName Displayed name of the User.
     *
     * @apiSuccessExample Success-Response:
     *      HTTP/1.1 200 OK
     *      {
     *        "Authorization": "Bearer &lt;your_jwt_token&gt;",
     *        "userId": 2,
     *        "username": "johndoe",
     *        "displayedName": "John Doe"
     *      }
     *
     * @apiError MissingParameter One or more parameter are not present.
     */
    public function authenticateUser (Request $request, Response $response, Array $args) {
        $post = $request->getParsedBody();

        print_r($post);

        if (!empty($post['login']) && !empty($post['password'])) {

            if ($userId = $this->userPasswordVerify($post['login'], $post['password'])) {

                $userInfos = fetchSQLReq($this->db, $this->sql["user"]["select"]["basicInfosFromId"],
                    array(":id" => $userId), false, true);

                $payload = array(
                    "iss" => "https://tfe.ags.ovh",
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
                $jwt = JWT::encode($payload, JWT_PRIVATE_KEY, 'RS256');

                $responseBody = array(
                    "Authorization"=>"Bearer ".$jwt,
                    "userId" => $userInfos["id"],
                    "username" => $userInfos["username"],
                    "displayedName" => $userInfos["displayedName"]
                );
                $response->getBody()->write(json_encode($responseBody));
                $response = $response->withHeader('Content-type', 'application/json');
            } else {
                $response = $response->withStatus(422);
            }
        } else {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array("error" => "MissingParameter")));
        }

        return $response;
    }

    private function userPasswordVerify($login="", $password="") {
        $login = filter_var($login, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $password = filter_var($password, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $dbPassword = fetchSQLReq($this->db, $this->sql["user"]["select"]["passwordFromLogin"], array(":username" => $login, ":email" => $login), true);

        if (!$dbPassword) {
            // User does not exist
            return false;
        } elseif (password_verify($password, $dbPassword)) {
            return fetchSQLReq($this->db, $this->sql["user"]["select"]["idFromLogin"], array(":username" => $login, ":email" => $login), true);
        } else {
            return false;
        }
    }

    public static function verifyJWT ($jwt="") {
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

    public static function verifyBearerAuthorization ($bearerAuthorization="") {
        list($jwt) = sscanf( $bearerAuthorization, "Bearer %s");

        return self::verifyJWT($jwt);
    }
}
?>