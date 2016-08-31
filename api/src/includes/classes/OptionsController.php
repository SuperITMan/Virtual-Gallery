<?php
use Slim\Http\Request;
use Slim\Http\Response;

class OptionsController {
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

    function getDefaultOptions (Request $request, Response $response, Array $args) {
        if ($data = fetchSQLReq($this->db, $this->sql["settings"]["select"]["all"], NULL, false, true)) {
            $data["aboutUs"] = empty($data["aboutUs"])?"false":"true";

            $response->getBody()->write(json_encode($data));
            $response = $response->withStatus(200);
        } else {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(array("error" => "ServerError")));
        }

        return $response;
    }

}
?>