<?php
require "init.php";
// notification_handler.php
$logFile = 'notification_log.txt';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Received request: " . print_r($_GET, true) . "\n", FILE_APPEND);
// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];
    
    // 3224771212
    //curl http://localhost/notification_handler.php?id=3224771407
    $payment = new GoPayPayment;

    $status = $payment->getStatus($paymentId);

    // $string = implode(', ', $status);
    echo $status . '\n';
    $paymentInformation = $payment->getIformation($paymentId);
    print_r($paymentInformation);

    //https://doc.gopay.com/#receiving-the-http-notification
    $paymentState = strtoupper($paymentInformation['state']);
    Db::dotaz("UPDATE transakce SET stav='$paymentState' WHERE id_transakce={$paymentInformation['order_number']}");
    
    if (str_contains($paymentState,'PAID')) {
        Db::dotaz("UPDATE transakce SET cas_zaplaceni=NOW(),zpusob_platby='{$paymentInformation['payment_instrument']}' WHERE id_transakce={$paymentInformation['order_number']}");
    }
   
}