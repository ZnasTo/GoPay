<?php
// Model pro zpracování notifikací, které přicházejí z GoPay, při změně stavu objednávky
//https://doc.gopay.com/#receiving-the-http-notification



class Notifications {
    // Funkce pro uložení notifikace a updatu stavu transakce

    public static function updateState($paymentId) {
        $payment = new GoPayPayment;
        
        // $status = $payment->getStatus($paymentId);

        $paymentInformation = $payment->getIformation($paymentId);

        if (!is_bool($paymentInformation)) {

            $paymentState = strtoupper($paymentInformation['state']);
            // Změní stav objednávky
            Db::query("UPDATE transakce SET stav='$paymentState' WHERE id_transakce={$paymentInformation['order_number']}");

            // Vloží záznam o notifikaci do databáze
            Db::query("INSERT INTO gopaynotifikace VALUES (NULL,'$paymentState',NOW(), '{$paymentInformation['order_number']}')");
            
            // Pokud je objednávka zaplacena nastaví čas zaplacení
            if (str_contains($paymentState,'PAID')) {
                Db::query("UPDATE transakce SET cas_zaplaceni=NOW(),zpusob_platby='{$paymentInformation['payment_instrument']}' WHERE id_transakce={$paymentInformation['order_number']}");
        
            }
        } else {
            $errorMsg = "Nastala chyba " . date('Y-m-d H:i:s') . "$paymentInformation";
            Db::query("INSERT INTO notifikaceError VALUES (NULL,'$errorMsg')");
        }
    }
}
