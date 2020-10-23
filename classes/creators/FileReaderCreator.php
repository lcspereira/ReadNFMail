<?php


namespace classes\creators;

require_once 'classes/creators/ReaderCreator.php';

use interfaces\Reader;
use classes\FileReader;

/**
 * Classe de criação do objeto FileReader
 * 
 * @author Lucas Pereira (lucas.pereira6c@gmail.com)
 *
 */
class FileReaderCreator extends ReaderCreator {
    
    /**
     * 
     * @param string $path
     */
    public function __construct(string $path) {
        $this->path = $path;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \classes\creators\ReaderCreator::getReader()
     */
    public function getReader() : Reader {
  
        return new FileReader($this->path);
    }
}