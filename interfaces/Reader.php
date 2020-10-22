<?php

namespace interfaces;

use classes\Payment;

interface Reader {
    /**
     * Lê e processa uma única mensagem.
     * 
     * @param int $id
     * @return Payment
     */
    public function read(string $id) : array;
    /**
     * Lê todas as novas mensagens.
     * 
     * @return array
     */
    public function readAll() : array;
    /**
     * Marca a mensagem como processada.
     * 
     * @param number $id
     * @return boolean
     */
    public function processed(int $id) : bool;
}