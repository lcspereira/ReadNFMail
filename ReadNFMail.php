<?php

use classes\ApiClient;
use classes\creators\ReaderCreator;
use classes\creators\MailReaderCreator;
use classes\creators\FileReaderCreator;

$apiAddr  = "http://localhost:8000/processar";
//$path     = "{imap.ethereal.mail:993/imap/tls/novalidade-cert}";
//$user     = 'arnulfo31@ethereal.email';
//$password = 'wt77saK6G4CJj1rtSh';
$path     = __DIR__ . "/test/mail/";


function processar (ReaderCreator $creator) {
    $api  = new ApiClient('http://localhost/process');
    foreach ($creator->readAll() as $pay) {
        $api->sendPayment($pay['payment']);
        $status = $api->getStatus();
        if ($status == 200) {
            $creator->processed($pay['id']);
        } else {
            echo "Erro de API: " . $status . "\n";
            echo "O pagamento não pôde ser processado.\n";
        }
    }
}

if ($user && $password) {
    processar(new MailReaderCreator($path, $user, $password), $apiAddr);
} else {
    processar(new FileReaderCreator($path), $apiAddr);
}