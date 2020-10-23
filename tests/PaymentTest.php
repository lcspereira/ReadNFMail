<?php
/**********************************************************
 * PaymentTest
 **********************************************************
 * Teste de processamento de mensagem e instanciamento do
 * objeto Payment através da mesma.
 *
 * @author: Lucas Pereira (lucas.pereira6c@gmail.com)
 **********************************************************/


require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../classes/Payment.php";

use PHPUnit\Framework\TestCase;
use classes\Payment;

final class PaymentTest extends TestCase {
    /**
     * Cria o objeto de pagamento processando a mensagem
     */
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
    
    /**
     * Testa o processamento de mensagem inválida.
     */
    public function testInvalidPayment() : void {
        $mail = "Mensagem inválida";
        $arrMail = explode("\n", $mail);
        
        $this->expectError();
        Payment::parsePaymentFromMail($arrMail, "0");
    }
}