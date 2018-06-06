<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 05/06/2018
 * Time: 15:40
 */

namespace Payment;

class Pagarme
{

    /*
     * Service Config
     */
    private $url;
    private $endPoint;
    private $apikey;

    /*
     * Param API
     */
    private $preset;
    private $params;
    private $transaction;

    /*
     * Return and Callback
     */
    private $callback;

    public function __construct($live = true)
    {
        $this->url = 'https://api.pagar.me';
        $this->transaction = [];

        if ($live == true) {
            $this->apikey = ''; // PRODUÇÃO
        } else {
            $this->apikey = 'ak_test_SUA_API_KEY'; // HOMOLOGAÇÃO
        }

        $this->preset = [
            'api_key' => $this->apikey,
            'postback_url' => 'https://www.seudominio.com.br/arquivo-postback.php'
        ];
    }

    /******************
     **** PAYMENT *****
     ******************/

    public function paymentRequest($amount, $installments = 1, $async = true)
    {
        $this->endPoint = '/1/transactions';

        $this->transaction += [
            'installments' => $installments,
            'amount' => $amount,
            'async' => $async
        ];

        $this->params = $this->transaction;

        $this->post();

        return $this->callback;
    }

    /******************
     **** CUSTOMER ****
     ******************/

    public function createCustomer($userName, $userEmail, $userDocument)
    {
        $this->endPoint = '/1/customers';

        $this->params = [
            'name' => $userName,
            'email' => $userEmail,
            'document_number' => $userDocument
        ];

        $this->post();

        $this->setTransactionCustomer();

        return $this->callback;
    }

    public function getCustomer($userCod)
    {
        $this->endPoint = "/1/customers/{$userCod}";
        $this->get();
        $this->setTransactionCustomer();
        return $this->callback;
    }

    /*********************
     **** CREDIT CARD ****
     *********************/

    public function createCreditCard($cardNumber, $cardHolderName, $cardCvv, $cardExpirationDate)
    {
        $this->endPoint = '/1/cards';

        $this->params = [
            'card_number' => $cardNumber,
            'card_expiration_date' => $cardExpirationDate,
            'card_cvv' => $cardCvv,
            'card_holder_name' => $cardHolderName
        ];

        $this->post();

        $this->setTransactionCreditCard();

        return $this->callback;
    }

    public function getCreditCard($cardCod)
    {
        $this->endPoint = "/1/cards/{$cardCod}";
        $this->get();
        $this->setTransactionCreditCard();
        var_dump($this->transaction);
        return $this->callback;
    }

    /******************
     **** BILLET ****
     ******************/

    public function billet()
    {
        $this->transaction += [
            'payment_method' => 'boleto'
        ];
    }

    /************************
     **** METHOD PRIVATE ****
     ************************/

    private function setTransactionCustomer()
    {
        $this->transaction += [
            'customer' => [
                'id' => $this->callback->id,
                'name' => $this->callback->name,
                'email' => $this->callback->email,
                'document_number' => $this->callback->document_number
            ]
        ];
    }

    private function setTransactionCreditCard()
    {
        $this->transaction += [
            'card_id' => $this->callback->id,
            'payment_method' => 'credit_card'
        ];
    }

    /************************
     ***** METHOD REST ******
     ************************/

    private function post()
    {
        $url = $this->url . $this->endPoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array_merge($this->params, $this->preset)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, []);
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }

    private function get()
    {
        $url = $this->url . $this->endPoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->preset));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }
}