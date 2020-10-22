<?php
namespace  classes\exception;

use Exception;

class PaymentException extends Exception {
    /**
     * Exceção para tratar os erros da classe pagamento.
     * 
     * @param string $message
     * @param number $code
     * @param mixed $previous
     */
    public function __construct($message, $code = -5, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * {@inheritDoc}
     * @see Exception::__toString()
     */
    public function __toString() : string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}