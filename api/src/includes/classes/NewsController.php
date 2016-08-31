<?php
use Slim\Http\Request;
use Slim\Http\Response;

class NewsController {
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

    function getLastNews (Request $request, Response $response, Array $args) {
        $sqlReq = $this->sql["news"]["select"]["lastNews"];
        $reqParams = $request->getQueryParams();
        if (array_key_exists ("limit",$reqParams))
            $sqlReq = $sqlReq . " LIMIT " . htmlspecialchars($reqParams["limit"]);

        if ($data = fetchSQLReq($this->db, $sqlReq)) {
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