<?php 

namespace classes;

use JsonSerializable;
use classes\exception\PaymentException;

/**
 * Le informações de pagamento de mensagens de e-mail,
 * e os carrega para API REST.
 * 
 * @author Lucas Pereira (lucas.pereira6c@gmail.com)
 *
 */
class Payment implements JsonSerializable {
    private $nome;
    private $endereco;
    private $valor;
    private $vencimento;
    private $nf;

    /**
     * 
     * @param string $nome
     * @param string $endereco
     * @param string $valor
     * @param string $vencimento
     * @param string $nf
     */
    public function __construct(string $nome, string $endereco, string $valor, string $vencimento, string $nf) {
        $this->nome       = $nome;
        $this->endereco   = $endereco;
        $this->valor      = $valor;
        $this->vencimento = $vencimento;
        $this->nf         = base64_encode($nf);
    }
    
    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }
    
    
    /**
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
        
    }
    
    
    /**
     * @return string
     */
    public function getEndereco() {
        return $this->endereco;
    }
    
    
    /**
     * @param string $endereco
     */
    public function setEndereco($endereco){
        $this->endereco = $endereco;
    }
    
    
    /**
     * @return number
     */
    public function getValor() {
        return $this->valor;
    }
    
    /**
     * @param number $valor
     */
    public function setValor($valor) {
        $this->valor = $valor;
        
    }
    
    
    /**
     * @return string
     */
    public function getVencimento() {
        return $this->vencimento;
    }
    
    
    /**
     * @param string $vencimento
     */
    public function setVencimento($vencimento) {
        $this->vencimento = $vencimento;
    }
    
    
    /**
     * @return string
     */
    public function getNF () {
        return base64_decode($this->nf);
    }
    
    
    /**
     * @param string $nf
     */
    public function setNF($nf) {
        $this->nf = base64_encode($nf);
    }
    
    
    /**
     * Instancia o objeto pagamento utilizando o texto das mensagens.
     * 
     * @param string $msg
     * @param string $nf
     * @return Payment
     */
    public static function parsePaymentFromMail ($msg, $nf) {
        $msgArr     = explode ("\n", $msg);
        $nome       = explode(": ", preg_grep('/^[Nn]ome:/', $msgArr)[2])[1];
        $endereco   = explode(": ", preg_grep('/^[Ee]ndereço:/', $msgArr)[3])[1];
        $valor      = explode(": ", preg_grep('/^[Vv]alor:/', $msgArr)[4])[1];
        $vencimento = explode(": ", preg_grep('/^[Vv]encimento:/', $msgArr)[5])[1];
        
        $payment    = new Payment($nome, $endereco, $valor, $vencimento, $nf);
        return $payment;
    }

    
    /**
     * Validação do objeto Payment.
     * 
     * @return number
     * @throws PaymentException
     */
    public function checkFields() {
        $errMsgTemplate = "Valor não encontrado: ";

        if (!$this->nome) {
            throw new PaymentException($errMsgTemplate . "nome");
        } else if (!$this->endereco) {
            throw new PaymentException($errMsgTemplate . "endereco");
        } else if (!$this->valor) {
            throw new PaymentException($errMsgTemplate . "valor");
        } else if (!$this->vencimento) {
            throw new PaymentException($errMsgTemplate . "vencimento");
        } else if (!$this->nf) {
            throw new PaymentException($errMsgTemplate . "nota fiscal");
        } else {
            return 0;
        }
    }

    
    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize() {
        return [
            'nome'       => $this->nome,
            'endereco'   => $this->endereco,
            'valor'      => $this->valor,
            'vencimento' => $this->vencimento,
            'nf'         => $this->nf
        ];
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        $str = "Nome: " . $this->nome . "\n";
        $str .= "Endereço: " . $this->endereco . "\n";
        $str .= "Valor: " . $this->valor . "\n";
        $str .= "Vencimento: " . $this->vencimento . "\n";
        $str .= "Nota (base64): " . $this->nf . "\n"; 
        //$str .= "Tamanho da nota fiscal: " .  + "\n";
        strlen($this->getNF());
        
        return $str;
    }
}