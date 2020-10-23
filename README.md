# ReadNFMail

## Instalação:

composer install

## Execução:

### Local:
php ReadNFMail.php -p<diretório de mensagens> -a<endereço da API>  
  
Diretório de mensagens deve ter a seguinte estrutura:  
< diretório de mensagens > > < id da mensagem > > msg.txt e nf.pdf  
  
### E-mail:
php ReadNFMail.php -p <string de conexão com servidor email> -u< email > -w< senha > -a< endereço da API >  
  
## Testes:
vendor/bin/phpunit tests/ ou vendor/bin/phpunit --testdox tests/  
  
Para executar todos os testes, deve-se configurar as seguintes variáveis de ambiente:  
TEST_MAILPATH_DIR=< diretório de mensagens >  
TEST_MAILPATH=< string de conexão >  
TEST_MAILUSER=< email >  
TEST_MAILPASSWORD=< senha >  

## String de conexão com servidor de email:
A string de conexão com o servidor de email deve ser escrita no formato que é utilizado na função imap_open do PHP.  
Para mais informações, acesse https://www.php.net/manual/en/function.imap-open.php.

## API de testes:
php -S < endereço >:< porta > TestApiServer.php
