<?php
/**********************************************************
 * TestApiServer
 **********************************************************
 * API para testes do ReadNFMail.
 *
 * Recebe requisição, instancia o objeto  do pagamento 
 * e faz verificação dos campos.
 * 
 * @author: Lucas Pereira (lucas.pereira6c@gmail.com)
 **********************************************************/

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/classes/Payment.php";

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use classes\Payment;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

/**
 * POST /process
 * 
 * Recebe o objeto Payment, e efetua verificação
 * dos atributos. 
 */
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