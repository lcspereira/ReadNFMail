<?php

namespace classes;

require_once __DIR__ . '/../interfaces/Reader.php';
require_once __DIR__ . '/../vendor/autoload.php';

use interfaces\Reader;
use PhpImap;
use PhpImap\Mailbox;


/**
 * Le informações de pagamento de mensagens de e-mail,
 * e os carrega para API REST.
 * 
 * @author lucas
 *
 */
class MailReader implements Reader {
    private $path;
    private $user;
    private $password;
    private $mailHandler;

    

    /**
     * @param string $path
     * @param string $user
     * @param string $password
     */
    public function __construct($path, $user, $password) {
        $this->path        = $path;
        $this->user        = $user;
        $this->password    = $password;
        $this->mailHandler = new \PhpImap\Mailbox($this->path, $this->user, $this->password);
    }
    
    /**
     * 
     */
    public function __destruct() {
        $this->mailHandler->disconnect();
    }
    
    /**
     * @return string
     */
    public function getPath() : string {
        return $this->path;
    }
    
    /**
     * @return string
     */
    public function getUser() : string {
        return $this->user;
    }
    
    /**
     * @return string
     */
    public function getPassword() : string {
        return $this->password;
    }
    
    /**
     * @return \PhpImap\Mailbox
     */
    public function getMailHandler() : Mailbox {
        return $this->mailHandler;
    }
    
    /**
     * @param string $path
     */
    public function setPath($path) : void{
        $this->path = $path;
    }
    
    /**
     * @param string $user
     */
    public function setUser($user) : void {
        $this->user = $user;
    }
    
    /**
     * @param string $password
     */
    public function setPassword($password) : void {
        $this->password = $password;
    }
    
    /**
     * @param \PhpImap\Mailbox $mailHandler
     */
    public function setMailHandler($mailHandler) : void {
        $this->mailHandler = $mailHandler;
    }

    /**
     * {@inheritDoc}
     * @see \interfaces\Reader::read()
     */
    public function read(string $id) : array {
        $mail   = $this->mailHandler->getMail($id, false);
        $txt    = $mail->textPlain;
        $attachs = $mail->getAttachments();
        $nf     = array_pop($attachs);
        $nf     = $nf->getContents();
        
        $pay    = Payment::parsePaymentFromMail($txt, $nf);
        $ret    = [
            'id'      => $id,
            'payment' => $pay
        ];
        return $ret;
    }
    
    /**
     * {@inheritDoc}
     * @see \interfaces\Reader::readAll()
     */
    public function readAll() : array {
        
        $arrPay  = [];
        $msgsIds = $this->mailHandler->searchMailbox('UNSEEN');
        foreach ($msgsIds as $n) {
            array_push($arrPay, $this->read($n));
        }
        return $arrPay;
    }
    
    
    /**
     * {@inheritDoc}
     * @see \interfaces\Reader::processed()
     */
    public function processed(int $id) : bool {
        try {
            $this->mailHandler->markMailAsRead($id);
            return true;
        } catch (\PhpImap\Exceptions\ConnectionException | \PhpImap\Exceptions\InvalidParameterException $ex) {
            return false;
        }
    }
}