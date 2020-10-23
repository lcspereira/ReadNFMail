<?php
/**********************************************************
 * ReadNFMail
 **********************************************************
 * Le informações de pagamento de mensagens de e-mail,
 * e os carrega para API REST.
 * 
 * @author: Lucas Pereira (lucas.pereira6c@gmail.com)
 **********************************************************/

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


$options  = getopt("p:a:u::w::");

// Processamento dos parâmetros de linha de comando
$apiAddr  = $options['a'];
$path     = $options['p'];
$user     = $options['u'];
$password = $options['w'];

/**
 * Função para converter o objeto criado em ReaderCreator
 * 
 * @param ReaderCreator $creator
 * @param string $apiAddr
 */
function processar (ReaderCreator $creator, string $apiAddr) {
    $api  = new ApiClient($apiAddr);
    foreach ($creator->readAll() as $pay) {
        try {
            echo "Processanto pagamento " . $pay['id'] . "...\n";
            echo "================================================\n";
            echo $pay['payment'];
            echo "================================================\n";
            $api->sendPayment($pay['payment']);
            echo "[ OK ]\n\n\n";
            $creator->processed($pay['id']);
        } catch (Exception $ex) {
            echo "[ ERRO ]\n";
            echo "Erro: Pagamento não pôde ser processado. (" . $ex->getMessage() . ")\n\n\n";
        }
    }
}

// Define qual objeto será utilizado
if ($user && $password) {
    processar(new MailReaderCreator($path, $user, $password), $apiAddr);
} else {
    processar(new FileReaderCreator($path), $apiAddr);
}
echo "-----------------------------\n";
echo "FIM DO PROGRAMA\n";
echo "-----------------------------\n";
exit (0);