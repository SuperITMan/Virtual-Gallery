<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App;
use \Firebase\JWT\JWT;

require "../vendor/autoload.php";
require "../config/config.php";
require "../includes/functions.php";
require "../includes/functions.sql.php";

require "../includes/classes/FileController.php";
require "../includes/classes/CreationController.php";
require "../includes/classes/OptionsController.php";
require "../includes/classes/NewsController.php";
require "../includes/classes/UserController.php";

$sql = json_decode(file_get_contents("../assets/config/sqlRequests.json"), true);

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new App(["settings" => $config]);
$container = $app->getContainer();

$container['db'] = function () {
    return connectDB(DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD);
};

$container["sql"] = $sql;

$container["CreationController"] = function($c) {
    return new CreationController($c["db"], $c["sql"]);
};

$container["OptionsController"] = function($c) {
    return new OptionsController($c["db"], $c["sql"]);
};

$container["NewsController"] = function($c) {
    return new NewsController($c["db"], $c["sql"]);
};

$container["UserController"] = function($c) {
    return new UserController($c["db"], $c["sql"]);
};

$container["FileController"] = function($c) {
    return new FileController($c["db"], $c["sql"]);
};
// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig("../includes/templates", [
//        'cache' => 'path/to/cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

$mw = function ($request, $response, $next) {
    $response = fillDefaultResponseHeader($response);
    $response = $next($request, $response);
    return $response;
};

$app->map(["GET", "POST", "UPDATE", "DELETE", "PUT"], "/", function (Request $request, Response $response, Array $args) {
    $response = $response->withStatus(400);
    $response->getBody()->write("For using this API, you need to specify a version. Go on <a href=\"https://github.com/superitman\">https://github.com/superitman</a> for more informations.");
});

$app->group("/v1", function() {
    $this->group("/creations", function () {
        $this->get("", "CreationController:getCreations");
        $this->post("", "CreationController:addCreation");

        $this->group("/{id:[0-9]+}", function () {
//            $this->get("", ["ProductController", "getProduct"]);

            $this->get("", "CreationController:getCreation");

            $this->get("/main-picture", function(Request $request, Response $response, Array $args) {

            });
        });

    });

    $this->group("/users", function () {
        $this->get("", "UserController:getUsers");
        $this->post("", "UserController:createUser");

        $this->group("/{id:[0-9]+}", function () {
            $this->get("", "UserController:getUser");
            $this->get("/creations", "CreationController:getAuthorCreations");
        });
    });

    $this->group("/auth", function () {
       $this->post("/login", "UserController:authenticateUser");
    });

    $this->group("/options", function () {
        $this->get("", "OptionsController:getDefaultOptions");
    });

    $this->group("/news", function () {
        $this->get("", "NewsController:getLastNews");
    });


//    $this->get("/uploads", "FileController:getPageUploads");
    $this->get("/upload", function ($request, $response, $args) {

    });
    $this->post("/upload", "FileController:uploadFiles");

});

$app->add($mw);

$app->run();



?>