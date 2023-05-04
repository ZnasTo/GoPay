
<?php

define("GO_ID","8565283375");
define("CLIENT_ID","1167493503");
define("CLIENT_SECERET","aeNk74bQ");
define("URL_PRODEJNIHO_MISTA","http://www.ales.recman.cz");

use GoPay\Definition\Language;
use GoPay\Definition\Payment\Currency;
use GoPay\Definition\Payment\PaymentInstrument;
use GoPay\Definition\Payment\BankSwiftCode;
use GoPay\Definition\Payment\VatRate;
use GoPay\Definition\Payment\PaymentItemType;

require("vendor/autoload.php");
class Payment
{   

    private $buyerData;
    private $cisloObjednavky;
    private $response;
    private $token;
    private $returnURL;


    function __construct(){

        $this->cisloObjednavky = intval(Db::dotazJeden(
            "SELECT MAX(cislo) 
            FROM objednavka;
            ")) + 1;
    }

    
    public function getGoPayUrl($buyerData, $returnURL){
        $this->buyerData = $buyerData;
        $this->returnURL = $returnURL;
        $this->createPayment();


    if($this->response->hasSucceed()){
            // print $this->response->json['gw_url'];
            return $this->response->json['gw_url'];        
    }
    else{   
            return 'error '.  print $this->response->statusCode;
        }
    }

    public function getStatus($statusID){
        return $this->token->getStatus($statusID);

    }

    private function createPayment(){ 
        // TODO data z formuláře

        
       $this->initialisePayment();

       $this->response = $this->token->createPayment([
        'payer' => [
            'default_payment_instrument' => PaymentInstrument::PAYMENT_CARD, 
            //     'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
            'allowed_payment_instruments' => [PaymentInstrument::BANK_ACCOUNT,PaymentInstrument::PAYMENT_CARD,PaymentInstrument::BITCOIN],
            //     'default_swift' => BankSwiftCode::FIO_BANKA,
            'allowed_swifts' => [BankSwiftCode::FIO_BANKA, BankSwiftCode::MBANK],
                'contact' => ['first_name' => 'Zbynek',
                        'last_name' => 'Zak',
                        'email' => 'testovaciEmail1@test.cz',
                        'phone_number' => '+420777456123',
                        'city' => 'C.Budejovice',
                        'street' => 'Plana 67',
                        'postal_code' => '373 01',
                        'country_code' => 'CZE'
                ]
        ],
        'amount' => 123612211,
        'currency' => Currency::CZECH_CROWNS,
        'order_number' => $this->cisloObjednavky,
    // 'order_description' => 'obuv',
    // 'items' => [[ // asi uplně není pro nas podstatné
    //         'type' => 'ITEM',
    //         'name' => 'obuv',
    //         'product_url' => 'https://www.eshop.cz/boty/lodicky',
    //         'ean' => 1234567890123,
    //         'amount' => 119990,
    //         'count' => 1,
    //         'vat_rate' => VatRate::RATE_4
    // ],
    //         [
    //         'type' => PaymentItemType::ITEM,
    //         'name' => 'oprava podpatku',
    //         'product_url' => 'https://www.eshop.cz/boty/opravy',
    //         'ean' => 1234567890189,
    //         'amount' => 19961,
    //         'count' => 1,
    //         'vat_rate' => VatRate::RATE_3
    //         ]],
    // EET bylo v česku zrušeno
    // ----------------------------------------
    //     'eet' => [
    //             'celk_trzba' => 139951,
    //             'zakl_dan1' => 99160,
    //             'dan1' => 20830,
    //             'zakl_dan2' => 17358,
    //             'dan2' => 2603,
    //             'mena' => Currency::CZECH_CROWNS
    //     ],
    // ----------------------------------------
    // 'additional_params' => [['name' => 'invoicenumber',
    //         'value' => '2015001003'
    // ]],
        'callback' => [
                // 'return_url' => 'http://localhost/GoPay/GoPay/View/checkout.php',
                'return_url' => $this->returnURL,
                // 'notification_url' => 'http://localhost/GoPay/GoPay/Controller/NotifyController.php' //hodne zajimava vec, sendne se kdykoli je status objednávky updatován
        ],
        'lang' => Language::CZECH

    ]);
    
    
    
    
}
private function initialisePayment(){
    $this->token = (
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

