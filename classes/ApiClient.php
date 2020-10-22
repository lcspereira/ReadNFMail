<?php
namespace classes;

use Exception;

class ApiClient {
    private $url;
    private $status;

    /**
     * @param string $url
     */
    public function __construct($url) {
        $this->url = $url;
    }
    
    /**
     * @return string
     */
    public function getUrl() : string {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getStatus() : int {
        return $this->status;
    }

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(int $status) {
        $this->status = $status;
    }

 
    /**
     * Envia dados de pagamento para a API.
     * 
     * @param Payment $pay
     * @throws Exception
     */
    public function sendPayment(Payment $pay) {
        $webError = false;

        try {
            $pay->checkFields();
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Connection: Keep-Alive'
            ));

            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($pay));
            curl_setopt($curl, CURLOPT_URL, $this->url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            curl_exec($curl);
            $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($this->status != 200) {
                $webError = true;
                throw new Exception (curl_error($curl));
            }
        } catch (Exception $ex) {
            throw new Exception ($ex->getMessage());
        } finally {
            if ($webError) {
                curl_close($curl);
            }
        }
    }
}