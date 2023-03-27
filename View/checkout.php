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
    // require_once("../Model/vendor/autoload.php");
    // require_once("../Controller/Controller.php");

    require_once("../Controller/PaymentController.php");
    
    $pc = new PaymentController;
    $url = $pc->getGoPayUrl("zde prijdou data");
    print $url;
?>
<form action="<?=$url?>" method="post">
  <button name="pay" type="submit">Zaplatit</button>
</form>
    
    </body>
</html>