<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../classes/Payment.php";

use PHPUnit\Framework\TestCase;
use classes\Payment;

final class PaymentTest extends TestCase {
    public function testCreatePaymentFromMail() : void {
        $mail    = "Bom dia,\n";
        $mail   .= "Segue meus dados de contato e informações para pagamento\n";
        $mail   .= "Nome: Guarida Imóveis\n";
        $mail   .= "Endereço: Protásio alves, 1309\n";
        $mail   .= "Valor: R$1.300,50\n";
        $mail   .= "Vencimento: 12/19\n";
        $mail   .= "Att.\n";
        
        $nf      = "nota fiscal";      
        $this->assertInstanceOf(Payment::class, Payment::parsePaymentFromMail($mail, $nf));
    }
    
    public function testInvalidPayment() : void {
        $mail = "Mensagem inválida";
        $arrMail = explode("\n", $mail);
        
        $this->expectError();
        Payment::parsePaymentFromMail($arrMail, "0");
    }
}