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
    public function getNome() : string {
        return $this->nome;
    }
    
    
    /**
     * @param string $nome
     */
    public function setNome(string $nome) : void {
        $this->nome = $nome;
        
    }
    
    
    /**
     * @return string
     */
    public function getEndereco() : string {
        return $this->endereco;
    }
    
    
    /**
     * @param string $endereco
     */
    public function setEndereco(string $endereco) : void{
        $this->endereco = $endereco;
    }
    
    
    /**
     * @return string
     */
    public function getValor() : string {
        return $this->valor;
    }
    
    /**
     * @param number $valor
     */
    public function setValor(string $valor) : void {
        $this->valor = $valor;
        
    }
    
    
    /**
     * @return string
     */
    public function getVencimento() : string {
        return $this->vencimento;
    }
    
    
    /**
     * @param string $vencimento
     */
    public function setVencimento(string $vencimento) : void {
        $this->vencimento = $vencimento;
    }
    
    
    /**
     * @return string
     */
    public function getNF () : string {
        return base64_decode($this->nf);
    }
    
    
    /**
     * @param string $nf
     */
    public function setNF(string $nf) : void {
        $this->nf = base64_encode($nf);
    }
    
    
    /**
     * Instancia o objeto pagamento utilizando o texto das mensagens.
     * 
     * @param string $msg
     * @param string $nf
     * @return Payment
     */
    public static function parsePaymentFromMail (string $msg, string $nf) : Payment {
        $msgArr     = explode ("\n", $msg);
        $nome       = explode(": ", current(preg_grep('/^[Nn]ome:/', $msgArr)))[1];
        $endereco   = explode(": ", current(preg_grep('/^[Ee]ndereço:/', $msgArr)))[1];
        $valor      = explode(": ", current(preg_grep('/^[Vv]alor:/', $msgArr)))[1];
        $vencimento = explode(": ", current(preg_grep('/^[Vv]encimento:/', $msgArr)))[1];
        $payment    = new Payment($nome, $endereco, $valor, $vencimento, $nf);
        return $payment;
    }

    
    /**
     * Validação do objeto Payment.
     * 
     * @return int
     * @throws PaymentException
     */
    public function checkFields() : int {
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
    public function jsonSerialize() : array {
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
    public function __toString() : string  {
        $str  = "Nome: " . $this->nome . "\n";
        $str .= "Endereço: " . $this->endereco . "\n";
        $str .= "Valor: " . $this->valor . "\n";
        $str .= "Vencimento: " . $this->vencimento . "\n";
        $str .= "Tamanho da nota: " . strlen(base64_decode($this->nf)) . "\n";
        
        return $str;
    }
}