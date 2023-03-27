<?php


use GoPay\Definition\Language;
use GoPay\Definition\Payment\Currency;
use GoPay\Definition\Payment\PaymentInstrument;
use GoPay\Definition\Payment\BankSwiftCode;
use GoPay\Definition\Payment\VatRate;
use GoPay\Definition\Payment\PaymentItemType;

require("../Model/PaymentInitialise.php");
require("Controller.php");

class PaymentController extends Controller
{   
    private $buyerData;
    private $response;
    

    private function paymentCreation(){ 
        // TODO data z formuláře
    
       $token = PaymentInitialise::initialisePayment();

       $this->response = $token->createPayment([
        'payer' => [
            'default_payment_instrument' => PaymentInstrument::PAYMENT_CARD, // podle toho co zakaznik vybere
            //     'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
            'allowed_payment_instruments' => [PaymentInstrument::BANK_ACCOUNT,PaymentInstrument::PAYMENT_CARD,PaymentInstrument::BITCOIN],
            //     'default_swift' => BankSwiftCode::FIO_BANKA,
            'allowed_swifts' => [BankSwiftCode::FIO_BANKA, BankSwiftCode::MBANK],
                'contact' => ['first_name' => 'Zbynek',
                        'last_name' => 'Zak',
                        'email' => 'testovaciEmail@test.cz',
                        'phone_number' => '+420777456123',
                        'city' => 'C.Budejovice',
                        'street' => 'Plana 67',
                        'postal_code' => '373 01',
                        'country_code' => 'CZE'
                ]
        ],
        'amount' => 139951,
        'currency' => Currency::CZECH_CROWNS,
        'order_number' => '001',// TODO budeme muste pomoci databaze zjistit
        'order_description' => 'obuv',// TODO zkusime pomazat potom
        'items' => [[
                'type' => 'ITEM',
                'name' => 'obuv',
                'product_url' => 'https://www.eshop.cz/boty/lodicky',
                'ean' => 1234567890123,
                'amount' => 119990,
                'count' => 1,
                'vat_rate' => VatRate::RATE_4
        ],
                [
                'type' => PaymentItemType::ITEM,
                'name' => 'oprava podpatku',
                'product_url' => 'https://www.eshop.cz/boty/opravy',
                'ean' => 1234567890189,
                'amount' => 19961,
                'count' => 1,
                'vat_rate' => VatRate::RATE_3
                ]],
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
        'additional_params' => [['name' => 'invoicenumber',
                'value' => '2015001003'
        ]],
        'callback' => [
                'return_url' => 'http://localhost/GoPay/GoPay/View/checkout.php',
                'notification_url' => 'http://www.your-url.tld/notify'
        ],
        'lang' => Language::CZECH

    ]);



    
    }

    function getGoPayUrl($buyerData){
        $this->buyerData = $buyerData;
        $this->paymentCreation();


    if($this->response->hasSucceed()){
            // print $this->response->json['gw_url'];
            return $this->response->json['gw_url'];
                
    }
    else {
        // TODO posle ho na error view
        // TODO nikam ho nepresmeruje nebo (vratiho na tu stranku)
        // return "url te stranky treba"
            return 'error '.  print $this->response->statusCode;

    }


    }


}
