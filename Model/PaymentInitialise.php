
<?php

define("GO_ID","8565283375");
define("CLIENT_ID","1167493503");
define("CLIENT_SECERET","aeNk74bQ");
define("URL_PRODEJNIHO_MISTA","http://www.ales.recman.cz");

require("vendor/autoload.php");
class PaymentInitialise
{
    static function initialisePayment(){
        return (
            GoPay\payments([
                'goid' => GO_ID,
                'clientId' => CLIENT_ID,
                'clientSecret' => CLIENT_SECERET,
                'gatewayUrl' => 'https://gw.sandbox.gopay.com/',
                'scope' => GoPay\Definition\TokenScope::ALL,
                'language' => GoPay\Definition\Language::CZECH,
                'timeout' => 30
            ])
            );
    }
    
}

