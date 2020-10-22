<?php

namespace classes\creators;

require_once 'classes/creators/ReaderCreator.php';

use interfaces\Reader;
use classes\FileReader;

class FileReaderCreator extends ReaderCreator {
    
    public function __construct(string $path) {
        $this->path = $path;
    }
    
    public function getReader() : Reader {
  
        return new FileReader($this->path);
    }
}