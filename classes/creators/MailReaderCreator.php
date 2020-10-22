<?php

namespace classes\creators;

require_once 'classes/creators/ReaderCreator.php';


use classes\MailReader;
use interfaces\Reader;

class MailReaderCreator extends ReaderCreator {
    private $user;
    private $password;
    
    public function __construct(string $path, string $user, string $password) {
        $this->path     = $path;
        $this->user     = $user;
        $this->password = $password;
    }
    
    public function getReader(): Reader {
        return new MailReader($this->path, $this->user, $this->password);
    }
}