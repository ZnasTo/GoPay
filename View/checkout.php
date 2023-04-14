
<?php
    require("htmlTop.php");
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
<?php
require("htmlBottom.html");
?>
