<?php

namespace classes;

require_once  __DIR__ . '/../interfaces/Reader.php';

use interfaces\Reader;
use classes\exception\PaymentException;

class FileReader implements Reader {
    
    private $path;
    
    public function __construct(string $path) {
        $this->path = $path;
    }
    /**
     * {@inheritDoc}
     * @see \interfaces\Reader::read()
     */
    public function read(string $id) : array {
        $pay = null;
        try {
            $msg = file_get_contents($this->path . "/" . $id . "/msg.txt");
            $nf  = file_get_contents($this->path . "/" . $id . "/nf.pdf");
            $pay = Payment::parsePaymentFromMail($msg, $nf);
            $ret = [
                'id'      => $id,
                'payment' => $pay
            ];
            return $ret;
        } catch (PaymentException $ex) {
            throw new PaymentException($ex->getMessage());
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \interfaces\Reader::readAll()
     */
    public function readAll() : array {
        $msgs = [];
        foreach (scandir($this->path) as $id){
            if ((preg_match("/_processed$/", $id) == 0) && ($id != ".") && ($id != "..")) {
                array_push($msgs, $this->read($id));
            }
        }
        return $msgs;
    }
    
    /**
     * {@inheritDoc}
     * @see \interfaces\Reader::processed()
     */
    public function processed(int $id) : bool {
        return rename($this->path . "/" . $id, $this->path . "/" . $id . "_processed");
    }
}