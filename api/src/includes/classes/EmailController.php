<?php
use Slim\Http\Request;
use Slim\Http\Response;

class FileController {
    private $db;
    private $sql;

    public function __construct($db, $sql) {
        $this->db = $db;
        $this->sql = $sql;
    }

    public function uploadFiles(Request $request, Response $response, Array $args) {
        $response = fillDefaultResponseHeader($response);

        if (!isset($_FILES['imageInput'])) {
            $response = $response->withStatus(409);
            print_r($_FILES);
            $response->getBody()->write("No file uploaded !");
        } else {

            $response = $response->withStatus(200);
            $response->getBody()->write("File uploaded!!!");
            return $response;

            $imgs = array();

            $files = $_FILES['uploads'];
            $cnt = count($files['name']);

            for ($i = 0; $i < $cnt; $i++) {
                if ($files['error'][$i] === 0) {
                    $name = uniqid('img-' . date('Ymd') . '-');
                    if (move_uploaded_file($files['tmp_name'][$i], '../uploads/' . $name) === true) {
                        $imgs[] = array('url' => '../uploads/' . $name, 'name' => $files['name'][$i]);
                    }

                }
            }

            $imageCount = count($imgs);

            if ($imageCount == 0) {
                echo 'No files uploaded!!  <p><a href="/">Try again</a>';
                return;
            }

            $plural = ($imageCount == 1) ? '' : 's';

            foreach ($imgs as $img) {
                printf('%s <img src="%s" width="50" height="50" /><br/>', $img['name'], $img['url']);
            }
        }
        return $response;
    }

}

?>