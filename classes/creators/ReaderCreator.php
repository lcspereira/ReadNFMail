<?php

namespace classes\creators;

use classes\Payment;
use interfaces\Reader;

abstract class ReaderCreator {
    private $path;
    
    
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
    
    
    public function read(int $id) : Payment {
        $reader = $this->getReader();
        return $reader->read($id);
    }
    
    public function readAll() : array {
        $reader = $this->getReader();
        return $reader->readAll();
    }
    
    public function processed(int $id) : bool {
        $reader = $this->getReader();
        return $reader->processed($id);
    }
}