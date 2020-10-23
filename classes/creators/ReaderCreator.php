<?php

namespace classes\creators;

use classes\Payment;
use interfaces\Reader;

/**
 * Classe abstrata para classes de criação de leitores.
 * 
 * @author lucas
 *
 */
abstract class ReaderCreator {
    private $path;
    
    /**
     * 
     * @return Reader
     */
    abstract public function getReader() : Reader;
    
    /**
     * @return mixed
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path) {
        $this->path = $path;
    }
    
    
    /**
     * Lê e processa uma única mensagem.
     * 
     * @param int $id
     * @return Payment
     */
    public function read(int $id) : Payment {
        $reader = $this->getReader();
        return $reader->read($id);
    }
    
    /**
     * Lê todas as novas mensagens.
     *
     * @return array
     */
    public function readAll() : array {
        $reader = $this->getReader();
        return $reader->readAll();
    }
    
    /**
     * Marca a mensagem como processada.
     *
     * @param number $id
     * @return boolean
     */
    public function processed(int $id) : bool {
        $reader = $this->getReader();
        return $reader->processed($id);
    }
}