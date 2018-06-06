<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 05/06/2018
 * Time: 15:40
 */

require_once __DIR__ . '/Pagarme.php';

$pagarme = new \Payment\Pagarme(false);

//$customer = $pagarme->createCustomer('Nome do cliente', 'email@cliente.com.br', '00000000000');
$customerClient = $pagarme->getCustomer('');

//$creditCard = $pagarme->createCreditCard('4111111111111111', 'Nome do cliente', '123', '1019');
$creditCardClient = $pagarme->getCreditCard('');

//$pagarme->billet();

$pay = $pagarme->paymentRequest('5000', 1, false);

var_dump($customerClient, $pay);