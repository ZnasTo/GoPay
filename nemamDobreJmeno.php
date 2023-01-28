<!-- GoPay REST API is avaliable in test mode 
at https://gw.sandbox.gopay.com/api.
 Production enviroment is located at https://gate.gopay.cz/api. -->

<?php
require_once("./src/GoPay.php");

$gopay = GoPay\Api::payments([
    'goid' => 'my goid',
    'clientId' => 'my id',
    'clientSecret' => 'my secret',
    'gatewayUrl' => 'gateway url'
]);


?>