<?php
// Kód pro zpracování notifikací, které přicházejí z GoPay, při změně stavu objednávky
//https://doc.gopay.com/#receiving-the-http-notification

require "init.php";

// // Soubor do kerého se ukládají notifikace
// $logFile = 'notification_log.txt';
// // 
// file_put_contents($logFile, date('Y-m-d H:i:s') . " - Received request: " . print_r($_GET, true) . "\n", FILE_APPEND);

// Kontrola zda je zadáno id
if (isset($_GET['id'])) {
    $paymentId = $_GET['id'];
    $payment = new GoPayPayment;

    $status = $payment->getStatus($paymentId);

    $paymentInformation = $payment->getIformation($paymentId);
    $paymentState = strtoupper($paymentInformation['state']);
    // Změní stav objednávky
    Db::query("UPDATE transakce SET stav='$paymentState' WHERE id_transakce={$paymentInformation['order_number']}");
    
    // Pokud je objednávka zaplacena nastaví čas zaplacení
    if (str_contains($paymentState,'PAID')) {
        Db::query("UPDATE transakce SET cas_zaplaceni=NOW(),zpusob_platby='{$paymentInformation['payment_instrument']}' WHERE id_transakce={$paymentInformation['order_number']}");
    }
   
}