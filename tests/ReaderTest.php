<?php
require_once __DIR__ . "/../classes/creators/FileReaderCreator.php";
require_once __DIR__ . "/../classes/creators/MailReaderCreator.php";
require_once __DIR__ . "/../classes/MailReader.php";
require_once __DIR__ . "/../classes/FileReader.php";
require_once __DIR__ . "/../interfaces/Reader.php";
require_once __DIR__ . "/../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use classes\creators\FileReaderCreator;
use classes\creators\MailReaderCreator;
use classes\MailReader;
use classes\Payment;
use classes\FileReader;
use classes\creators\ReaderCreator;

/**
 * Teste dos objetos leitores de mensagens.
 * 
 * @author Lucas Pereira (lucas.pereira6c@gmail.com)
 *
 */
class ReaderTest extends TestCase {
    /**
     * Testa criação do objeto FileReader através do factory method.
     */
    public function testFileReaderInstantiation () : void {
        $path = getenv('TEST_MAILPATH_DIR');
        
        if (!$path) {
            $path = "./test/";
        }
        $creator = new FileReaderCreator($path);
        $this->assertInstanceOf(ReaderCreator::class, $creator);
        $this->assertInstanceOf(FileReader::class, $creator->getReader());
    }
    
    /**
     * Testa criação do objeto MailReader através do factory method
     */
    public function testMailReaderInstantiation () : void {
        $path     = getenv('TEST_MAILPATH');
        $user     = getenv('TEST_MAILUSER');
        $password = getenv('TEST_MAILPASSWORD');
        
        
        if (!$path) {
            $path = "{imap.test.com:993/imap/tls}INBOX";
        }
        
        if (!$user || !$password) {
            $user     = "test@test.com";
            $password = "123456";
        }
        $creator = new MailReaderCreator($path, $user, $password);
        $this->assertInstanceOf(ReaderCreator::class, $creator);
        $this->assertInstanceOf(MailReader::class, $creator->getReader());
    }
    
    /**
     * Teste de leitura de mensagens e instanciação do objeto Payment
     * por meio de processamento de mensagem em arquivo.
     */
    public function testFileReaderExec () : void {
        $path = getenv('TEST_MAILPATH_DIR');
        if ($path) {
            $arq  = new FileReaderCreator($path);
            $pays = $arq->readAll();
            
            if (count($pays) == 0) {
                echo "(" . __FUNCTION__ . ") Aviso: Não há mensagens a serem processadas.\n";
            }
            
            foreach ($pays as $p) {
                $this->assertInstanceOf(Payment::class, $p['payment']);
            }
        } else {
            echo "Para executar este teste, configure a variável de ambiente TEST_MAILPATH.\n";
        }
    }
    
    /**
     * Teste de leitura de mensagens e instanciação do objeto Payment
     * por meio de processamento de mensagem em email.
     */
    public function testMailReaderExec () : void {
        $path     = getenv('TEST_MAILPATH');
        $user     = getenv('TEST_MAILUSER');
        $password = getenv('TEST_MAILPASSWORD');
        
        if ($path && $user && $password) {
            $mrc   = new MailReaderCreator($path, $user, $password);
            $pays = $mrc->readAll();
            
            if (count($pays) == 0) {
                echo "(" . __FUNCTION__ . ") Aviso: Não há mensagens a serem processadas.\n";
            }
            
            foreach ($pays as $p) {
                $this->assertInstanceOf(Payment::class, $p['payment']);
            }
        } else {
            echo "Para executar este teste, configure as variáveis de ambiente TEST_MAILPATH, TEST_MAILUSER e TEST_MAILPASSWORD.\n";
        }
    }
}