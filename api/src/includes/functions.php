<?php

use Slim\Http\Response;

function authenticateUser () {

}

function fillDefaultResponseHeader (Response $response) {
    return $response
            ->withHeader('Access-Control-Allow-Origin', "*")
            ->withHeader('Access-Control-Allow-Headers', array('Content-Type', 'X-Requested-With', 'Authorization'))
            ->withHeader('Access-Control-Request-Method', 'GET, POST')
            ->withHeader('Access-Control-Allow-Credentials', true);
}
?>