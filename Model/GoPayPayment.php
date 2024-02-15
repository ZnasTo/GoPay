<?php
// Třída pro práci s platební bránou gopay

// Dokumentace GoPay api
// https://doc.gopay.com/


// Informace potřebné pro správnou funcki platební brány
define("GO_ID","8565283375");
define("CLIENT_ID","1167493503");
define("CLIENT_SECERET","aeNk74bQ");
define("URL_PRODEJNIHO_MISTA","http://www.ales.recman.cz");


// Import potřebných GoPay tříd
use GoPay\Definition\Language;
use GoPay\Definition\Payment\Currency;
use GoPay\Definition\Payment\PaymentInstrument;
use GoPay\Definition\Payment\BankSwiftCode;
use GoPay\Definition\Payment\VatRate;
use GoPay\Definition\Payment\PaymentItemType;


// Připojení composer autoload souboru
require("vendor/autoload.php");

class GoPayPayment extends Payment
{   

    private $buyerData;
    private $notificationURL;
    private $response;
    private $token;
    private $returnURL;


    // Metoda vrací odkaz na platební bránu, jinak vrátí error
    //https://doc.gopay.cz/#error-scope
    public function getUrl($parameters){
        $this->buyerData = $parameters["buyerData"];
        $this->returnURL = $parameters["returnURL"];
        $this->notificationURL = $parameters["notificationURL"];
        

        $this->createPayment();


    if($this->response->hasSucceed()){
            return $this->response->json['gw_url'];        
    }
    else{   
            return 'error '.  $this->response->statusCode;
        }
    }
    // Metoda pro vrácení stavu objednávky
    public function getStatus($statusID){
        $this->initialisePayment();
        $response = $this->token->getStatus($statusID);
        if ($response->hasSucceed()) {
            $responseBody = $response->json;
            $paymentStatus = $responseBody['state'];
        }
        return $paymentStatus;

    }

    // Metoda pro získání informací o objednávce
    public function getIformation($statusID){
        $this->initialisePayment();
        $response = $this->token->getStatus($statusID);
        if ($response->hasSucceed()) {
            $responseBody = $response->json;
        }
        return $responseBody;

    }
    // Metoda pro vytvoření platby
    private function createPayment(){ 
        
       $this->initialisePayment();

       $this->response = $this->token->createPayment([
        'payer' => [
            'default_payment_instrument' => PaymentInstrument::PAYMENT_CARD, 
            //     'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
            'allowed_payment_instruments' => [PaymentInstrument::PAYMENT_CARD],
            //     'default_swift' => BankSwiftCode::FIO_BANKA,
            'allowed_swifts' => [BankSwiftCode::FIO_BANKA, BankSwiftCode::MBANK],
                'contact' => ['first_name' => $this->buyerData["jmeno"],
                        'last_name' => $this->buyerData["prijmeni"],
                        'email' => $this->buyerData["email"],
                        'phone_number' => "+" . $this->buyerData["telefon"],
                        'city' => $this->buyerData["mesto"],
                        'street' => $this->buyerData["ulice"] . ' ' . $this->buyerData["CP"],
                        'postal_code' => $this->buyerData["PSC"],
                        'country_code' => 'CZE'
                ]
        ],
        'amount' => $this->buyerData["castka"],
        'currency' => Currency::CZECH_CROWNS,
        'order_number' => $this->buyerData["cislo_objednavky"],
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
                'notification_url' => 'http://idkneco.wz.cz/notification_handler.php'
        ],
        'lang' => Language::CZECH

    ]);
    
    
    
    
}

// Metoda pro inicializaci platby
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

    
<<<<<<< HEAD
}
=======
}

>>>>>>> origin/main
