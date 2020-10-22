<?php

require_once __DIR__ . "/../classes/ApiClient.php";
require_once __DIR__ . "/../classes/Payment.php";

use PHPUnit\Framework\TestCase;
use classes\ApiClient;
use classes\Payment;

class ApiTest extends TestCase {
    public function testApiPaymentPost () : void {
        $url = getenv('TEST_API_URL');
        
        if (!$url) {
            $url = "http://localhost:8000/process";
        }
        
        $mail    = "Bom dia,\n";
        $mail   .= "Segue meus dados de contato e informações para pagamento\n";
        $mail   .= "Nome: Guarida Imóveis\n";
        $mail   .= "Endereço: Protásio alves, 1309\n";
        $mail   .= "Valor: R$1.300,50\n";
        $mail   .= "Vencimento: 12/19\n";
        $mail   .= "Att.\n";
        
        $nf      = "nota fiscal";
        
        $api = new ApiClient($url);
        $api->sendPayment(Payment::parsePaymentFromMail($mail, $nf));
        $this->assertEquals('200', $api->getStatus());
        
    }
}