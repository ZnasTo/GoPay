<!-- GoPay REST API is avaliable in test mode 
at https://gw.sandbox.gopay.com/api.
 Production enviroment is located at https://gate.gopay.cz/api. -->

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

// musi zde byt pro funkci composeru
require("vendor/autoload.php"); 

//vytvoreni nove platby
// URL prodejního místa: http://www.ales.recman.cz
// Test GoID: 8565283375
// Test SecureKey: zSwQwtAAF6V2Kr7cWVdbVYkW
// Test ClientID: 1167493503
// Test ClientSecret: aeNk74bQ
// Test uživatelské jméno: testUser8565283375
// Test heslo: P0004331
define("GO_ID","8565283375");
define("CLIENT_ID","1167493503");
define("CLIENT_SECERET","aeNk74bQ");
define("URL_PRODEJNIHO_MISTA","http://www.ales.recman.cz");

$gopay = GoPay\payments([
        'goid' => GO_ID,
        'clientId' => CLIENT_ID,
        'clientSecret' => CLIENT_SECERET,
        'gatewayUrl' => 'https://gw.sandbox.gopay.com/',
        'scope' => GoPay\Definition\TokenScope::ALL,
        'language' => GoPay\Definition\Language::CZECH,
        'timeout' => 30
    ]);
    

use GoPay\Definition\Language;
use GoPay\Definition\Payment\Currency;
use GoPay\Definition\Payment\PaymentInstrument;
use GoPay\Definition\Payment\BankSwiftCode;
use GoPay\Definition\Payment\VatRate;
use GoPay\Definition\Payment\PaymentItemType;

$response = $gopay->createPayment([
    'payer' => [
            'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
            'allowed_payment_instruments' => [PaymentInstrument::BANK_ACCOUNT],
            'default_swift' => BankSwiftCode::FIO_BANKA,
            'allowed_swifts' => [BankSwiftCode::FIO_BANKA, BankSwiftCode::MBANK],
            'contact' => ['first_name' => 'Zbynek',
                    'last_name' => 'Zak',
                    'email' => 'test@test.cz',
                    'phone_number' => '+420777456123',
                    'city' => 'C.Budejovice',
                    'street' => 'Plana 67',
                    'postal_code' => '373 01',
                    'country_code' => 'CZE'
            ]
    ],
    'amount' => 139951,
    'currency' => Currency::CZECH_CROWNS,
    'order_number' => '001',
    'order_description' => 'obuv',
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
    'additional_params' => [['name' => 'invoicenumber',
            'value' => '2015001003'
    ]],
    'callback' => [
            'return_url' => 'https://www.eshop.cz/return',
            'notification_url' => 'https://www.eshop.cz/notify'
    ],
    'lang' => Language::CZECH
]);

// print $response;
//TODO nejaky error handeling mozna
$url = null;
if ($response->hasSucceed()) {
        print $response->json['gw_url'];
        $url = $response->json['gw_url'];
        
}
else {
        print 'error';
        print $response->statusCode;

}
?>
<form action="<?=$url?>" method="post">
  <button name="pay" type="submit">Zaplatit</button>
</form>
    
    </body>
</html>