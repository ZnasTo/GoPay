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

        $paymentInformation = $payment->getInformation($paymentId);
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

    // Oderslání změny stavu objednávky jinemu oddeleni

    public static function sendNotification($paymentId) {
        $payment = new GoPayPayment;
        $paymentInformation = $payment->getInformation($paymentId);
        if (!is_bool($paymentInformation)) {
            $paymentState = strtoupper($paymentInformation['state']);

            // ziskani oddeleni ke kteremu patri objednavka
            $department = Db::queryOne("SELECT oddeleni FROM transakce WHERE id_transakce={$paymentInformation['order_number']}");

            $department = $department['oddeleni'];

            if ($department == "platebni_brana") {
                return;
            }

            //ziskani cisla objednavky z databaze
            $cislo_objednavky = Db::queryOne("SELECT cislo_objednavky FROM transakce WHERE id_transakce={$paymentInformation['order_number']}");

            // ziskani url na kterou se ma poslat notifikace
            $queryResult = Db::queryOne("SELECT notification_url, api_token FROM oddeleni WHERE id_oddeleni={$department['oddeleni']}");

            if ($queryResult == 1) {
                return;
            }

            $apiToken = $queryResult["api_token"];
            $url = $queryResult["notification_url"];
            //send data to the correct department
            //send token in the header
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 
                HTTP_BUILD_QUERY(array(
                    "id" => $cislo_objednavky, 
                    "stav" => $paymentState['state'], 
                    "castka" => $paymentState['amount']
                ))
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '. $apiToken
            ));
            $response = curl_exec($ch);
            curl_close($ch);
        }
    }
}