<?php

namespace classes\creators;

require_once 'classes/creators/ReaderCreator.php';


use classes\MailReader;
use interfaces\Reader;

/**
 * Classe de criação do objeto MailReader.
 * 
 * @author lucas
 *
 */
class MailReaderCreator extends ReaderCreator {
    private $user;
    private $password;
    
    /**
     * 
     * @param string $path
     * @param string $user
     * @param string $password
     */
    public function __construct(string $path, string $user, string $password) {
        $this->path     = $path;
        $this->user     = $user;
        $this->password = $password;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \classes\creators\ReaderCreator::getReader()
     */
    public function getReader(): Reader {
        return new MailReader($this->path, $this->user, $this->password);
    }
}