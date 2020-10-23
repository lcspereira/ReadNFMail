<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../classes/Payment.php";

use PHPUnit\Framework\TestCase;
use classes\Payment;

/**
 * Teste de processamento de mensagem e instanciamento do
 * objeto Payment através da mesma.
 * 
 * @author Lucas Pereira (lucas.pereira6c@gmail.com)
 *
 */
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
        $pay1    = Payment::parsePaymentFromMail($mail, $nf);
        $this->assertInstanceOf(Payment::class, $pay1);
        $this->assertEquals("Guarida Imóveis", $pay1->getNome());
        $this->assertEquals("Protásio alves, 1309", $pay1->getEndereco());
        $this->assertEquals("R$1.300,50", $pay1->getValor());
        $this->assertEquals("12/19", $pay1->getVencimento());
        $this->assertEquals("nota fiscal", $pay1->getNF());
        
        // Mudando ordem dos campos
        $mail    = "Bom dia,\n";
        $mail   .= "Segue meus dados de contato e informações para pagamento\n";
        $mail   .= "Nome: Guarida Imóveis\n";
        $mail   .= "Vencimento: 12/19\n";
        $mail   .= "Valor: R$1.300,50\n";
        $mail   .= "Endereço: Protásio alves, 1309\n";
        $mail   .= "Att.\n";
        
        $nf      = "nota fiscal";
        $pay2    = Payment::parsePaymentFromMail($mail, $nf);
        
        $this->assertInstanceOf(Payment::class, $pay2);
        
        $this->assertEquals($pay1, $pay2);
        
    }
    
    /**
     * Testa o processamento de mensagem inválida.
     */
    public function testInvalidPayment() : void {
        $mail = "Mensagem inválida";
        
        $this->expectError();
        Payment::parsePaymentFromMail($mail, "0");
    }
    
}