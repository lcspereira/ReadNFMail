<?php
require_once 'classes/creators/ReaderCreator.php';
require_once 'classes/creators/FileReaderCreator.php';
require_once 'classes/creators/MailReaderCreator.php';

require_once 'interfaces/Reader.php';
require_once 'classes/FileReader.php';
require_once 'classes/MailReader.php';
require_once 'classes/ApiClient.php';
require_once 'classes/Payment.php';

require_once 'classes/exception/PaymentException.php';


use classes\ApiClient;
use classes\creators\ReaderCreator;
use classes\creators\MailReaderCreator;
use classes\creators\FileReaderCreator;

//$path     = "{imap.ethereal.mail:993/imap/tls/novalidade-cert}";
//$user     = 'arnulfo31@ethereal.email';
//$password = 'wt77saK6G4CJj1rtSh';

$options  = getopt("p:a:u::w::");


$apiAddr  = $options['a'];
$path     = $options['p'];
$user     = $options['u'];
$password = $options['w'];

function processar (ReaderCreator $creator, string $apiAddr) {
    $api  = new ApiClient($apiAddr);
    foreach ($creator->readAll() as $pay) {
        try {
            $api->sendPayment($pay['payment']);
            $creator->processed($pay['id']);
        } catch (Exception $ex) {
            echo "Erro: Pagamento não pôde ser processado. (" . $ex->getMessage() . ")";
        }
    }
}

if ($user && $password) {
    processar(new MailReaderCreator($path, $user, $password), $apiAddr);
} else {
    processar(new FileReaderCreator($path), $apiAddr);
}