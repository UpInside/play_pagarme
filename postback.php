<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 05/06/2018
 * Time: 16:57
 */

header('Content-Type: text/html; charset=UTF-8');

$postback = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($postback)) {
    $pagarmeLog = null;
    foreach ($postback['transaction'] as $key => $value) {
        $pagarmeLog .= "{$key}: {$value}\r\n";
    }

    $pagarmeLogFile = fopen('_log_pagarme.txt', 'a+');
    fwrite($pagarmeLogFile, "\r\n########## " . date('d/m/Y H\hi') . " ##########\r\n\r\n{$pagarmeLog}<br><br>");
    fclose($pagarmeLogFile);
}