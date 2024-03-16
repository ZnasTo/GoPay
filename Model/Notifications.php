<?php
// Model pro zpracování notifikací, které přicházejí z GoPay, při změně stavu objednávky
//https://doc.gopay.com/#receiving-the-http-notification

// Testovaci notifikace
// curl http://localhost/notification?id=3224771407



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
        } 
    }

    // Oderslání změny stavu objednávky jinemu oddeleni

    public static function sendNotification($paymentId) {
        $payment = new GoPayPayment;
        $paymentInformation = $payment->getIformation($paymentId);
        if (!is_bool($paymentInformation)) {
            $paymentState = strtoupper($paymentInformation['state']);

            // ziskani oddeleni ke kteremu patri objednavka
            $department = Db::queryOne("SELECT oddeleni FROM transakce WHERE id_transakce={$paymentInformation['order_number']}");

            $department = $department['oddeleni'];

            if ($department == "platebni_brana") {
                return;
            }

            //ziskani cisla objednavky z databaze
            $cisloObjednavky = Db::queryOne("SELECT cislo_objednavky FROM transakce WHERE id_transakce={$paymentInformation['order_number']}");

            // ziskani url na kterou se ma poslat notifikace
            $url = Db::queryOne("SELECT url FROM oddeleni WHERE id_oddeleni={$department['oddeleni']}");

            // odeslani notifikace

            $url = $url['url'];
            $url .= "?cisloObjednavky={$cisloObjednavky['cislo_objednavky']}&stav={$paymentState}";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

        }
    }
}