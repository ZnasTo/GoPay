<!-- https://help.gopay.com/en/knowledge-base/integration-of-payment-gateway/integration-of-payment-gateway-1/how-do-i-integrate-the-payment-gateway -->
<?php
    require("htmlTop.php");
    // require_once("../Model/vendor/autoload.php");
    // require_once("../Controller/Controller.php");

    require_once("../Controller/PaymentController.php");
    ?>
    <ul>
    <li><a href="checkout.php">checkout</a></li>
      <li><a href="../index.phtml">Hlavní Stránka</a></li>
    </ul>
    <?php
    $pc = new PaymentController;
    $url = $pc->getGoPayUrl("zde prijdou data");
    print $url;
    if(isset($_GET["id"])){
      $response = $pc->getStatus($_GET["id"]);

      $responseArray = json_decode($response,true );
      // print_r($responseArray);
      $state = $responseArray["state"];
      print "<p class='state' >" . $state . "</p>";
      if($state != "PAID"){
        $url = $responseArray["gw_url"];
        //TODO dodelej pro jine statusy 
      }
      
      
      
    }
    //TODO tlacitko by se nemelo vykreslovat kdyz je to zaplacene,
    //  ale to muzeme vyresit tak ze ho presmerujeme na nejakou stranku "dekujeme za platbu"
    // cislo objednavky:"..." ($_POST)
    ?>
<form action="<?=$url?>" method="post">
  <button name="pay" type="submit">Zaplatit</button>
</form>
<?php
require("htmlBottom.html");
?>
