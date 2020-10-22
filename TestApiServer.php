<?php

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/classes/Payment.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use classes\Payment;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->post('/process', function (Request $request, Response $response, $args) {
    $pay = null;
    try {
        $data = json_decode($request->getBody()->getContents(), true);
        $pay  = new Payment($data['nome'], $data['endereco'], $data['valor'], $data['vencimento'], base64_decode($data['nf']));
        $pay->checkFields();
        $response = $response->withStatus(200);
    } catch (Exception $ex) {
        $response = $response->withStatus(500, $ex->getMessage());
    } finally {
        $response->getBody()->write(sprintf("%s", $pay));
        return $response;
    }
});

$app->run();